<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ApplicationsExport;
use App\Exports\ApplicationsCsvExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use ZipArchive;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // Basic stats
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        // Today's applications
        $todayApplications = Application::whereDate('created_at', today())->count();
        
        // Weekly applications
        $weeklyApplications = Application::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        // Monthly applications
        $monthlyApplications = Application::whereMonth('created_at', now()->month)->count();

        // Recent applications
        $recentApplications = Application::latest()->limit(10)->get();
        
        // Class-wise statistics
        $classwiseStats = Application::select('class_applied')
                                   ->selectRaw('count(*) as total')
                                   ->groupBy('class_applied')
                                   ->orderBy('total', 'desc')
                                   ->get();

        // Gender-wise statistics
        $genderStats = Application::select('gender')
                                ->selectRaw('count(*) as total')
                                ->groupBy('gender')
                                ->get();

        // Monthly trend data for chart
        $monthlyTrend = Application::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, count(*) as total')
                                 ->whereYear('created_at', now()->year)
                                 ->groupBy('year', 'month')
                                 ->orderBy('month')
                                 ->get();

        // Quick stats
        $quickStats = [
            'today' => $todayApplications,
            'weekly' => $weeklyApplications,
            'monthly' => $monthlyApplications,
            'conversion_rate' => $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100, 1) : 0
        ];

        return view('admin.dashboard', compact(
            'stats', 'recentApplications', 'classwiseStats', 
            'genderStats', 'monthlyTrend', 'quickStats'
        ));
    }

    public function applications(Request $request)
    {
        $query = Application::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('class')) {
            $query->byClass($request->class);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->latest()->paginate(20);

        $classes = Application::distinct()->pluck('class_applied');

        return view('admin.applications', compact('applications', 'classes'));
    }

    public function show(Application $application)
    {
        return view('admin.application-detail', compact('application'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
            'test_date' => 'nullable|date',
            'test_venue' => 'nullable|string'
        ]);

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'test_date' => $request->test_date,
            'test_venue' => $request->test_venue
        ]);

        return back()->with('success', 'Application status updated successfully!');
    }

    public function exportApplications(Request $request)
    {
        $query = Application::query();

        // Apply same filters as applications list
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('class')) {
            $query->byClass($request->class);
        }

        $applications = $query->get();

        $format = $request->input('format', 'xlsx');

        if ($format === 'csv') {
            return Excel::download(new ApplicationsCsvExport($applications), 'applications-' . date('Y-m-d') . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new ApplicationsExport($applications), 'applications-' . date('Y-m-d') . '.xlsx');
    }

    public function generateAdmitCard(Application $application)
    {
        if ($application->status !== 'approved') {
            return back()->with('error', 'Only approved applications can generate admit cards!');
        }

        // Generate QR Code
        $qrCode = new QrCode($application->application_id);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $pdf = Pdf::loadView('application.admit-card', compact('application', 'qrCodeBase64'));
        
        return $pdf->download('admit-card-' . $application->application_id . '.pdf');
    }

    public function bulkAdmitCards(Request $request)
    {
        $applicationIds = $request->application_ids;
        
        if (empty($applicationIds)) {
            return back()->with('error', 'Please select applications to generate admit cards!');
        }

        $applications = Application::whereIn('id', $applicationIds)
                                 ->where('status', 'approved')
                                 ->get();

        if ($applications->isEmpty()) {
            return back()->with('error', 'No approved applications found!');
        }

        // Create a temporary directory for PDFs
        $tempDir = storage_path('app/temp/admit-cards-' . time());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        foreach ($applications as $application) {
            // Generate QR Code using Endroid QR Code
            $qrCode = new QrCode($application->application_id);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrCodeBase64 = base64_encode($result->getString());
            
            $pdf = Pdf::loadView('application.admit-card', compact('application', 'qrCodeBase64'));
            $pdf->save($tempDir . '/admit-card-' . $application->application_id . '.pdf');
        }

        // Create ZIP file
        $zip = new ZipArchive();
        $zipFileName = 'admit-cards-' . date('Y-m-d-H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $files = glob($tempDir . '/*.pdf');
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();

            // Clean up temp directory
            array_map('unlink', glob($tempDir . '/*.pdf'));
            rmdir($tempDir);

            return response()->download($zipPath)->deleteFileAfterSend();
        }

        return back()->with('error', 'Failed to create ZIP file!');
    }

    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'application_ids' => 'required|string'
        ]);

        $applicationIds = json_decode($request->application_ids, true);
        
        if (empty($applicationIds)) {
            return back()->with('error', 'No applications selected!');
        }

        $updatedCount = Application::whereIn('id', $applicationIds)
                                 ->update(['status' => $request->status]);

        return back()->with('success', "Successfully updated {$updatedCount} application(s) to {$request->status}!");
    }
}
