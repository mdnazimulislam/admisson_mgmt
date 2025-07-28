<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ApplicationsCsvExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles
{
    protected $applications;

    public function __construct($applications)
    {
        $this->applications = $applications;
    }

    public function collection()
    {
        return $this->applications;
    }

    public function headings(): array
    {
        return [
            'Application ID',
            'Student Name (English)',
            'Student Name (Bangla)',
            'Birth Date',
            'Age',
            'Gender',
            'Religion',
            'Class Applied',
            'Father Name',
            'Mother Name',
            'Guardian Name',
            'Father Occupation',
            'Mother Occupation',
            'Contact Phone',
            'Contact Email',
            'Address',
            'Status',
            'Test Date',
            'Test Venue',
            'Admin Notes',
            'Applied Date',
            'Applied Time'
        ];
    }

    public function map($application): array
    {
        return [
            $application->application_id,
            $application->student_name_en,
            $application->student_name_bn ?: 'N/A',
            $application->birth_date->format('Y-m-d'),
            $application->birth_date->diffInYears(now()) . ' years',
            ucfirst($application->gender),
            $application->religion,
            $application->class_applied,
            $application->father_name,
            $application->mother_name,
            $application->guardian_name ?: 'N/A',
            $application->father_occupation,
            $application->mother_occupation,
            "'" . $application->contact_phone, // Add single quote to preserve phone number formatting
            $application->contact_email ?: 'N/A',
            $application->address,
            ucfirst($application->status),
            $application->test_date ? $application->test_date->format('Y-m-d H:i') : 'Not Set',
            $application->test_venue ?: 'Not Set',
            $application->admin_notes ?: 'None',
            $application->created_at->format('Y-m-d'),
            $application->created_at->format('H:i:s')
        ];
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_TEXT, // Phone number column
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
