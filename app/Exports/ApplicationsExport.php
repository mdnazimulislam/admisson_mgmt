<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping
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
            'Applied Date'
        ];
    }

    public function map($application): array
    {
        return [
            $application->application_id,
            $application->student_name_en,
            $application->student_name_bn,
            $application->birth_date->format('Y-m-d'),
            ucfirst($application->gender),
            $application->religion,
            $application->class_applied,
            $application->father_name,
            $application->mother_name,
            $application->guardian_name,
            $application->father_occupation,
            $application->mother_occupation,
            $application->contact_phone,
            $application->contact_email,
            $application->address,
            ucfirst($application->status),
            $application->test_date ? $application->test_date->format('Y-m-d H:i') : '',
            $application->test_venue,
            $application->created_at->format('Y-m-d H:i')
        ];
    }
}
