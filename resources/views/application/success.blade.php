@extends('layouts.app')

@section('title', 'Application Successful - Boni School')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>Application Submitted Successfully!
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">
                                <i class="fas fa-thumbs-up me-2"></i>Congratulations!
                            </h4>
                            <p class="mb-0">
                                Your admission application has been submitted successfully. 
                                Please keep your Application ID for future reference.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-id-badge me-2"></i>Application Details
                                    </h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Application ID:</strong></td>
                                            <td class="text-primary fs-5 fw-bold">{{ $application->application_id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Student Name:</strong></td>
                                            <td>{{ $application->student_name_en }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Class Applied:</strong></td>
                                            <td>{{ $application->class_applied }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge {{ $application->status_badge }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Submitted On:</strong></td>
                                            <td>{{ $application->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-info-circle me-2"></i>What's Next?
                                    </h5>
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <span class="small fw-bold">1</span>
                                        </div>
                                        <div>
                                            <strong>Review Process</strong>
                                            <p class="small text-muted mb-0">Our admin team will review your application within 2-3 business days.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <span class="small fw-bold">2</span>
                                        </div>
                                        <div>
                                            <strong>Status Update</strong>
                                            <p class="small text-muted mb-0">You can check your application status using your Application ID.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <span class="small fw-bold">3</span>
                                        </div>
                                        <div>
                                            <strong>Admit Card</strong>
                                            <p class="small text-muted mb-0">If approved, you can download your admit card and attend the test.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Important Notes:
                                    </h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-primary me-2"></i>
                                            Please save your <strong>Application ID: {{ $application->application_id }}</strong> for future reference.
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-primary me-2"></i>
                                            You will be contacted on the provided phone number if additional information is needed.
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-primary me-2"></i>
                                            Ensure your contact details are accurate for smooth communication.
                                        </li>
                                        <li class="mb-0">
                                            <i class="fas fa-arrow-right text-primary me-2"></i>
                                            For any queries, you can use our AI chat assistant or contact the school office.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="fas fa-search me-2"></i>Check Status
                        </button>
                        
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Go to Home
                        </a>
                        
                        <button class="btn btn-info" onclick="toggleChat()">
                            <i class="fas fa-robot me-2"></i>Chat Assistant
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Print Application Details
    function printDetails() {
        window.print();
    }

    // Copy Application ID to clipboard
    function copyApplicationId() {
        const applicationId = '{{ $application->application_id }}';
        navigator.clipboard.writeText(applicationId).then(function() {
            Swal.fire({
                title: 'Copied!',
                text: 'Application ID copied to clipboard',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
</script>
@endpush
@endsection
