<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admit Card - {{ $application->application_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .admit-card-title {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
            margin-top: 10px;
        }
        .content {
            display: table;
            width: 100%;
        }
        .left-section {
            display: table-cell;
            width: 70%;
            vertical-align: top;
            padding-right: 20px;
        }
        .right-section {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            text-align: center;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px 5px;
            border-bottom: 1px solid #ddd;
        }
        .label {
            font-weight: bold;
            width: 40%;
        }
        .value {
            border-bottom: 1px solid #333;
            min-height: 20px;
        }
        .photo-placeholder {
            width: 120px;
            height: 140px;
            border: 2px solid #333;
            display: inline-block;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }
        .qr-code {
            width: 100px;
            height: 100px;
            border: 1px solid #333;
            display: inline-block;
        }
        .instructions {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .instructions h4 {
            margin-top: 0;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature-left {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-right {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 150px;
            margin: 0 auto;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">BONI SCHOOL</div>
        <div>Excellence in Education</div>
        <div class="admit-card-title">ADMISSION TEST ADMIT CARD</div>
    </div>

    <div class="content">
        <div class="left-section">
            <table class="info-table">
                <tr>
                    <td class="label">Application ID:</td>
                    <td class="value">{{ $application->application_id }}</td>
                </tr>
                <tr>
                    <td class="label">Student Name:</td>
                    <td class="value">{{ $application->student_name_en }}</td>
                </tr>
                @if($application->student_name_bn)
                <tr>
                    <td class="label">Name (Bangla):</td>
                    <td class="value">{{ $application->student_name_bn }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Father's Name:</td>
                    <td class="value">{{ $application->father_name }}</td>
                </tr>
                <tr>
                    <td class="label">Mother's Name:</td>
                    <td class="value">{{ $application->mother_name }}</td>
                </tr>
                <tr>
                    <td class="label">Date of Birth:</td>
                    <td class="value">{{ $application->birth_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Gender:</td>
                    <td class="value">{{ ucfirst($application->gender) }}</td>
                </tr>
                <tr>
                    <td class="label">Class Applied:</td>
                    <td class="value">{{ $application->class_applied }}</td>
                </tr>
                @if($application->test_date)
                <tr>
                    <td class="label">Test Date:</td>
                    <td class="value">{{ $application->test_date->format('d M Y, h:i A') }}</td>
                </tr>
                @endif
                @if($application->test_venue)
                <tr>
                    <td class="label">Test Venue:</td>
                    <td class="value">{{ $application->test_venue }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Contact Phone:</td>
                    <td class="value">{{ $application->contact_phone }}</td>
                </tr>
            </table>
        </div>

        <div class="right-section">
            <div class="photo-placeholder">
                @if($application->student_photo)
                    <img src="{{ asset('storage/' . $application->student_photo) }}" 
                         style="width: 100%; height: 100%; object-fit: cover;" alt="Student Photo">
                @else
                    <div style="line-height: 140px; color: #666;">Photo</div>
                @endif
            </div>
            
            <div>
                <strong>QR Code</strong><br>
                @if(isset($qrCodeBase64))
                    <img src="data:image/png;base64,{{ $qrCodeBase64 }}" class="qr-code" alt="QR Code">
                @else
                    <div class="qr-code" style="line-height: 100px; color: #666;">QR Code</div>
                @endif
            </div>
        </div>
    </div>

    <div class="instructions">
        <h4>Instructions for Admission Test:</h4>
        <ul>
            <li>Please bring this admit card on the test day.</li>
            <li>Arrive at the test center at least 30 minutes before the test time.</li>
            <li>Bring a valid ID proof (Birth certificate or any government issued ID).</li>
            <li>Bring necessary stationery items (pen, pencil, eraser, etc.).</li>
            <li>Mobile phones and electronic devices are not allowed in the test room.</li>
            <li>Follow all instructions given by the test supervisors.</li>
        </ul>
    </div>

    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-line"></div>
            <div>Student/Guardian Signature</div>
        </div>
        <div class="signature-right">
            <div class="signature-line"></div>
            <div>Authorized Signature</div>
        </div>
    </div>

    <div class="footer">
        <p>This is a computer-generated admit card and does not require a signature.</p>
        <p>For any queries, contact: admissions@bonischool.edu | Phone: [School Phone Number]</p>
        <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
    </div>
</body>
</html>
