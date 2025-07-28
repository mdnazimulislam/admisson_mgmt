@extends('layouts.app')

@section('title', 'Application Status - Boni School')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-search me-2"></i>Application Status
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user me-2"></i>Student Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Application ID:</strong></td>
                                            <td class="text-primary fw-bold">{{ $application->application_id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $application->student_name_en }}</td>
                                        </tr>
                                        @if($application->student_name_bn)
                                        <tr>
                                            <td><strong>Name (Bangla):</strong></td>
                                            <td>{{ $application->student_name_bn }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Class Applied:</strong></td>
                                            <td>{{ $application->class_applied }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender:</strong></td>
                                            <td>{{ ucfirst($application->gender) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date of Birth:</strong></td>
                                            <td>{{ $application->birth_date->format('d M Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Application Status
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <span class="badge {{ $application->status_badge }} fs-6 px-3 py-2">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </div>
                                    
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Applied On:</strong></td>
                                            <td>{{ $application->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                        @if($application->test_date)
                                        <tr>
                                            <td><strong>Test Date:</strong></td>
                                            <td class="text-success fw-bold">{{ $application->test_date->format('d M Y, h:i A') }}</td>
                                        </tr>
                                        @endif
                                        @if($application->test_venue)
                                        <tr>
                                            <td><strong>Test Venue:</strong></td>
                                            <td class="text-success fw-bold">{{ $application->test_venue }}</td>
                                        </tr>
                                        @endif
                                        @if($application->admin_notes)
                                        <tr>
                                            <td><strong>Notes:</strong></td>
                                            <td>{{ $application->admin_notes }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Progress -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-tasks me-2"></i>Application Progress
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="text-center">
                                            <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="small fw-bold text-success">Submitted</div>
                                            <div class="small text-muted">{{ $application->created_at->format('d M Y') }}</div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <div class="rounded-circle {{ $application->status != 'pending' ? 'bg-success' : 'bg-warning' }} text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                                @if($application->status != 'pending')
                                                    <i class="fas fa-check"></i>
                                                @else
                                                    <i class="fas fa-clock"></i>
                                                @endif
                                            </div>
                                            <div class="small fw-bold {{ $application->status != 'pending' ? 'text-success' : 'text-warning' }}">Reviewed</div>
                                            <div class="small text-muted">
                                                @if($application->status != 'pending')
                                                    {{ $application->updated_at->format('d M Y') }}
                                                @else
                                                    In Progress
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <div class="rounded-circle {{ $application->status == 'approved' ? 'bg-success' : ($application->status == 'rejected' ? 'bg-danger' : 'bg-secondary') }} text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                                @if($application->status == 'approved')
                                                    <i class="fas fa-thumbs-up"></i>
                                                @elseif($application->status == 'rejected')
                                                    <i class="fas fa-times"></i>
                                                @else
                                                    <i class="fas fa-hourglass-half"></i>
                                                @endif
                                            </div>
                                            <div class="small fw-bold {{ $application->status == 'approved' ? 'text-success' : ($application->status == 'rejected' ? 'text-danger' : 'text-muted') }}">
                                                {{ ucfirst($application->status) }}
                                            </div>
                                            <div class="small text-muted">
                                                @if($application->status != 'pending')
                                                    Final Status
                                                @else
                                                    Pending
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        @if($application->status == 'approved')
                            <a href="{{ route('application.admit-card', $application->application_id) }}" class="btn btn-success">
                                <i class="fas fa-download me-2"></i>Download Admit Card
                            </a>
                        @endif
                        
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Go to Home
                        </a>
                        
                        <button class="btn btn-info" onclick="toggleChat()">
                            <i class="fas fa-robot me-2"></i>Need Help?
                        </button>
                    </div>

                    <!-- Status-specific messages -->
                    @if($application->status == 'pending')
                        <div class="alert alert-info mt-4" role="alert">
                            <h6 class="alert-heading">
                                <i class="fas fa-clock me-2"></i>Application Under Review
                            </h6>
                            <p class="mb-0">
                                Your application is currently being reviewed by our admin team. 
                                This process typically takes 2-3 business days. We will update your status soon.
                            </p>
                        </div>
                    @elseif($application->status == 'approved')
                        <div class="alert alert-success mt-4" role="alert">
                            <h6 class="alert-heading">
                                <i class="fas fa-check-circle me-2"></i>Application Approved!
                            </h6>
                            <p class="mb-0">
                                Congratulations! Your application has been approved. 
                                You can now download your admit card and prepare for the admission test.
                                @if($application->test_date)
                                    <br><strong>Test Date:</strong> {{ $application->test_date->format('d M Y, h:i A') }}
                                @endif
                                @if($application->test_venue)
                                    <br><strong>Venue:</strong> {{ $application->test_venue }}
                                @endif
                            </p>
                        </div>
                    @elseif($application->status == 'rejected')
                        <div class="alert alert-danger mt-4" role="alert">
                            <h6 class="alert-heading">
                                <i class="fas fa-times-circle me-2"></i>Application Not Approved
                            </h6>
                            <p class="mb-0">
                                Unfortunately, your application could not be approved at this time.
                                @if($application->admin_notes)
                                    <br><strong>Reason:</strong> {{ $application->admin_notes }}
                                @endif
                                <br>You may contact the school office for more information or apply again next year.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
