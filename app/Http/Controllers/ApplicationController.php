<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function create()
    {
        $classes = [
            'Play Group',
            'Nursery',
            'KG',
            'Class 1',
            'Class 2',
            'Class 3',
            'Class 4',
            'Class 5',
            'Class 6',
            'Class 7',
            'Class 8',
            'Class 9'
        ];
        
        return view('application.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_name_en' => 'required|string|max:255',
            'student_name_bn' => 'nullable|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'religion' => 'required|string|max:100',
            'class_applied' => 'required|string',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'contact_phone' => 'required|string|max:15',
            'contact_email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'student_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'guardian_nid' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['application_id'] = Application::generateApplicationId();

        // Handle file uploads
        if ($request->hasFile('student_photo')) {
            $data['student_photo'] = $request->file('student_photo')->store('photos', 'public');
        }

        if ($request->hasFile('birth_certificate')) {
            $data['birth_certificate'] = $request->file('birth_certificate')->store('documents', 'public');
        }

        if ($request->hasFile('guardian_nid')) {
            $data['guardian_nid'] = $request->file('guardian_nid')->store('documents', 'public');
        }

        $application = Application::create($data);

        return redirect()->route('application.success', $application->application_id)
                        ->with('success', 'Application submitted successfully!');
    }

    public function success($applicationId)
    {
        $application = Application::where('application_id', $applicationId)->firstOrFail();
        return view('application.success', compact('application'));
    }

    public function downloadAdmitCard($applicationId)
    {
        $application = Application::where('application_id', $applicationId)
                                 ->where('status', 'approved')
                                 ->firstOrFail();

        // Generate QR Code
        $qrCode = new QrCode($application->application_id);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $pdf = Pdf::loadView('application.admit-card', compact('application', 'qrCodeBase64'));
        
        return $pdf->download('admit-card-' . $application->application_id . '.pdf');
    }

    public function checkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $application = Application::where('application_id', $request->application_id)->first();

        if (!$application) {
            return back()->with('error', 'Application not found!');
        }

        return view('application.status', compact('application'));
    }
}
