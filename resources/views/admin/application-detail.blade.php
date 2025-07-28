@extends('layouts.admin')

@section('title', 'Application Details - ' . $application->application_id)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">📄 Application Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.applications') }}">Applications</a></li>
                    <li class="breadcrumb-item active">{{ $application->application_id }}</li>
                </ol>
            </nav>
        </div>
        
        <div class="d-flex gap-2">
            @if($application->status === 'approved')
                <a href="{{ route('admin.admit-card', $application) }}" 
                   class="btn btn-success btn-sm">
                    <i class="fas fa-id-card mr-1"></i> Download Admit Card
                </a>
            @endif
            
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-edit mr-1"></i> Update Status
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="updateStatus('approved')">
                        <i class="fas fa-check text-success mr-2"></i> Approve
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="updateStatus('rejected')">
                        <i class="fas fa-times text-danger mr-2"></i> Reject
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="updateStatus('pending')">
                        <i class="fas fa-clock text-warning mr-2"></i> Mark Pending
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-cog text-info mr-2"></i> Advanced Update
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Application Overview Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">👤 Student Information</h6>
                    <span class="badge {{ $application->status_badge }} badge-lg">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Student Photo -->
                        <div class="col-md-3 text-center mb-4">
                            @if($application->student_photo)
                                <img src="{{ Storage::url($application->student_photo) }}" 
                                     alt="Student Photo" 
                                     class="img-fluid rounded border"
                                     style="max-width: 200px; max-height: 250px;"
                                     onclick="showImageModal('{{ Storage::url($application->student_photo) }}', 'Student Photo')">
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center" 
                                     style="width: 200px; height: 250px; margin: 0 auto;">
                                    <i class="fas fa-user fa-4x text-muted"></i>
                                </div>
                            @endif
                            <div class="mt-2">
                                <strong>{{ $application->application_id }}</strong>
                            </div>
                        </div>

                        <!-- Student Details -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label text-muted">Student Name (English)</label>
                                    <div class="fw-bold">{{ $application->student_name_en }}</div>
                                </div>
                                
                                @if($application->student_name_bn)
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label text-muted">Student Name (Bangla)</label>
                                        <div class="fw-bold">{{ $application->student_name_bn }}</div>
                                    </div>
                                @endif
                                
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label text-muted">Date of Birth</label>
                                    <div>{{ $application->birth_date->format('F d, Y') }}</div>
                                    <small class="text-muted">
                                        Age: {{ $application->birth_date->diffInYears(now()) }} years old
                                    </small>
                                </div>
                                
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label text-muted">Gender</label>
                                    <div>{{ ucfirst($application->gender) }}</div>
                                </div>
                                
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label text-muted">Religion</label>
                                    <div>{{ $application->religion }}</div>
                                </div>
                                
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label text-muted">Class Applied For</label>
                                    <div class="fw-bold text-primary">{{ $application->class_applied }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Information -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">👪 Parent/Guardian Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">Father's Name</label>
                            <div class="fw-bold">{{ $application->father_name }}</div>
                        </div>
                        
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">Mother's Name</label>
                            <div class="fw-bold">{{ $application->mother_name }}</div>
                        </div>
                        
                        @if($application->guardian_name)
                            <div class="col-sm-6 mb-3">
                                <label class="form-label text-muted">Guardian's Name</label>
                                <div class="fw-bold">{{ $application->guardian_name }}</div>
                            </div>
                        @endif
                        
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">Father's Occupation</label>
                            <div>{{ $application->father_occupation }}</div>
                        </div>
                        
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">Mother's Occupation</label>
                            <div>{{ $application->mother_occupation }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📞 Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <div>
                                <i class="fas fa-phone text-primary mr-2"></i>
                                <a href="tel:{{ $application->contact_phone }}">{{ $application->contact_phone }}</a>
                            </div>
                        </div>
                        
                        @if($application->contact_email)
                            <div class="col-sm-6 mb-3">
                                <label class="form-label text-muted">Email Address</label>
                                <div>
                                    <i class="fas fa-envelope text-primary mr-2"></i>
                                    <a href="mailto:{{ $application->contact_email }}">{{ $application->contact_email }}</a>
                                </div>
                            </div>
                        @endif
                        
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted">Address</label>
                            <div>{{ $application->address }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📊 Application Status</h6>
                </div>
                <div class="card-body text-center">
                    <div class="status-icon mb-3">
                        @if($application->status === 'approved')
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        @elseif($application->status === 'rejected')
                            <i class="fas fa-times-circle fa-4x text-danger"></i>
                        @else
                            <i class="fas fa-clock fa-4x text-warning"></i>
                        @endif
                    </div>
                    
                    <h5 class="mb-3">
                        <span class="badge {{ $application->status_badge }} p-2" style="font-size: 1rem;">
                            {{ ucfirst($application->status) }}
                        </span>
                    </h5>
                    
                    <div class="text-muted mb-3">
                        <small>Applied on {{ $application->created_at->format('F d, Y \a\t h:i A') }}</small>
                    </div>
                    
                    @if($application->test_date)
                        <div class="alert alert-info">
                            <strong>Test Date:</strong><br>
                            {{ $application->test_date->format('F d, Y \a\t h:i A') }}
                            @if($application->test_venue)
                                <br><strong>Venue:</strong> {{ $application->test_venue }}
                            @endif
                        </div>
                    @endif
                    
                    @if($application->admin_notes)
                        <div class="alert alert-secondary">
                            <strong>Admin Notes:</strong><br>
                            {{ $application->admin_notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📋 Uploaded Documents</h6>
                </div>
                <div class="card-body">
                    <!-- Birth Certificate -->
                    @if($application->birth_certificate)
                        <div class="document-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-alt text-primary mr-2"></i>
                                    <strong>Birth Certificate</strong>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="viewDocument('{{ Storage::url($application->birth_certificate) }}', 'Birth Certificate')">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <a href="{{ Storage::url($application->birth_certificate) }}" 
                                       download 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Guardian NID -->
                    @if($application->guardian_nid)
                        <div class="document-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-id-card text-primary mr-2"></i>
                                    <strong>Guardian NID</strong>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="viewDocument('{{ Storage::url($application->guardian_nid) }}', 'Guardian NID')">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <a href="{{ Storage::url($application->guardian_nid) }}" 
                                       download 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!$application->birth_certificate && !$application->guardian_nid)
                        <div class="text-center text-muted">
                            <i class="fas fa-file-slash fa-2x mb-2"></i>
                            <p>No documents uploaded</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">⚡ Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($application->status === 'approved')
                            <a href="{{ route('admin.admit-card', $application) }}" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-id-card mr-2"></i> Generate Admit Card
                            </a>
                        @endif
                        
                        <button type="button" 
                                class="btn btn-primary btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#statusModal">
                            <i class="fas fa-edit mr-2"></i> Update Status
                        </button>
                        
                        <a href="tel:{{ $application->contact_phone }}" 
                           class="btn btn-info btn-sm">
                            <i class="fas fa-phone mr-2"></i> Call Parent
                        </a>
                        
                        @if($application->contact_email)
                            <a href="mailto:{{ $application->contact_email }}" 
                               class="btn btn-secondary btn-sm">
                                <i class="fas fa-envelope mr-2"></i> Send Email
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.applications.update-status', $application) }}">
                @csrf
                @method('PATCH')
                
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Application Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $application->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="test_date" class="form-label">Test Date (Optional)</label>
                        <input type="datetime-local" 
                               class="form-control" 
                               id="test_date" 
                               name="test_date" 
                               value="{{ $application->test_date ? $application->test_date->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="test_venue" class="form-label">Test Venue (Optional)</label>
                        <input type="text" 
                               class="form-control" 
                               id="test_venue" 
                               name="test_venue" 
                               value="{{ $application->test_venue }}"
                               placeholder="Enter test venue">
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" 
                                  id="admin_notes" 
                                  name="admin_notes" 
                                  rows="3"
                                  placeholder="Add any notes or comments">{{ $application->admin_notes }}</textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document/Image Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="documentImage" src="" alt="Document" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Quick Status Update Form (Hidden) -->
<form id="quickStatusForm" method="POST" action="{{ route('admin.applications.update-status', $application) }}" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="quickStatus">
</form>
@endsection

@push('scripts')
<script>
function updateStatus(status) {
    if (confirm(`Are you sure you want to ${status} this application?`)) {
        document.getElementById('quickStatus').value = status;
        document.getElementById('quickStatusForm').submit();
    }
}

function showImageModal(imageSrc, title) {
    document.getElementById('documentModalLabel').textContent = title;
    document.getElementById('documentImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('documentModal')).show();
}

function viewDocument(documentSrc, title) {
    showImageModal(documentSrc, title);
}
</script>
@endpush
