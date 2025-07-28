@extends('layouts.admin')

@section('title', 'Applications Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📋 Application Management</h1>
        <div class="d-none d-lg-inline-block">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download mr-1"></i> Export Filtered
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <form method="GET" action="{{ route('admin.applications.export') }}" class="d-inline">
                            @foreach(request()->query() as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <input type="hidden" name="format" value="xlsx">
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-file-excel text-success mr-2"></i> Excel (.xlsx)
                            </button>
                        </form>
                    </li>
                    <li>
                        <form method="GET" action="{{ route('admin.applications.export') }}" class="d-inline">
                            @foreach(request()->query() as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <input type="hidden" name="format" value="csv">
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-file-csv text-info mr-2"></i> CSV (.csv)
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">🔍 Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.applications') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Name, ID, Phone...">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">All Classes</option>
                            @foreach($classes as $classOption)
                                <option value="{{ $classOption }}" {{ request('class') == $classOption ? 'selected' : '' }}>
                                    {{ $classOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.applications') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                📊 Applications List 
                <span class="badge badge-secondary">{{ $applications->total() }} total</span>
            </h6>
            
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" 
                        id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cogs mr-1"></i> Bulk Actions
                </button>
                <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                    <li><a class="dropdown-item" href="#" onclick="bulkUpdateStatus('approved')">
                        <i class="fas fa-check text-success mr-2"></i> Approve Selected
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="bulkUpdateStatus('rejected')">
                        <i class="fas fa-times text-danger mr-2"></i> Reject Selected
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="bulkAdmitCards()">
                        <i class="fas fa-file-pdf text-primary mr-2"></i> Generate Admit Cards
                    </a></li>
                </ul>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="applicationsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Application ID</th>
                            <th>Student Name</th>
                            <th>Class Applied</th>
                            <th>Father Name</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                        <tr>
                            <td>
                                <input type="checkbox" 
                                       class="application-checkbox" 
                                       value="{{ $application->id }}"
                                       onchange="updateBulkActionsState()">
                            </td>
                            <td>
                                <strong class="text-primary">{{ $application->application_id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($application->student_photo)
                                        <img src="{{ Storage::url($application->student_photo) }}" 
                                             alt="Student Photo" 
                                             class="rounded-circle me-2" 
                                             width="40" height="40">
                                    @else
                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $application->student_name_en }}</div>
                                        @if($application->student_name_bn)
                                            <small class="text-muted">{{ $application->student_name_bn }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $application->class_applied }}</td>
                            <td>{{ $application->father_name }}</td>
                            <td>
                                <div>
                                    <i class="fas fa-phone text-primary"></i> {{ $application->contact_phone }}
                                </div>
                                @if($application->contact_email)
                                    <div>
                                        <i class="fas fa-envelope text-secondary"></i> {{ $application->contact_email }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $application->status_badge }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $application->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $application->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.applications.show', $application) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($application->status === 'approved')
                                        <a href="{{ route('admin.admit-card', $application) }}" 
                                           class="btn btn-success btn-sm" 
                                           title="Download Admit Card">
                                            <i class="fas fa-id-card"></i>
                                        </a>
                                    @endif
                                    
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-primary btn-sm dropdown-toggle" 
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false"
                                                title="Quick Status Update">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" 
                                                   href="#" 
                                                   onclick="quickStatusUpdate('{{ $application->id }}', 'approved')">
                                                <i class="fas fa-check text-success mr-2"></i> Approve
                                            </a></li>
                                            <li><a class="dropdown-item" 
                                                   href="#" 
                                                   onclick="quickStatusUpdate('{{ $application->id }}', 'rejected')">
                                                <i class="fas fa-times text-danger mr-2"></i> Reject
                                            </a></li>
                                            <li><a class="dropdown-item" 
                                                   href="#" 
                                                   onclick="quickStatusUpdate('{{ $application->id }}', 'pending')">
                                                <i class="fas fa-clock text-warning mr-2"></i> Mark Pending
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <h5>No applications found</h5>
                                    <p>Try adjusting your search criteria or filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($applications->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} 
                        of {{ $applications->total() }} results
                    </div>
                    <div>
                        {{ $applications->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Status Update Form (Hidden) -->
<form id="quickStatusForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="quickStatus">
</form>

<!-- Bulk Actions Form (Hidden) -->
<form id="bulkActionsForm" method="POST" action="{{ route('admin.bulk-admit-cards') }}" style="display: none;">
    @csrf
    <input type="hidden" name="application_ids" id="bulkApplicationIds">
</form>
@endsection

@push('scripts')
<script>
let selectedApplications = [];

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.application-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActionsState();
}

function updateBulkActionsState() {
    const checkboxes = document.querySelectorAll('.application-checkbox:checked');
    const bulkActionsBtn = document.getElementById('bulkActionsDropdown');
    
    selectedApplications = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedApplications.length > 0) {
        bulkActionsBtn.classList.remove('disabled');
        bulkActionsBtn.textContent = `Bulk Actions (${selectedApplications.length})`;
    } else {
        bulkActionsBtn.classList.add('disabled');
        bulkActionsBtn.textContent = 'Bulk Actions';
    }
}

function quickStatusUpdate(applicationId, status) {
    if (confirm(`Are you sure you want to ${status} this application?`)) {
        const form = document.getElementById('quickStatusForm');
        form.action = `/admin/applications/${applicationId}/status`;
        document.getElementById('quickStatus').value = status;
        form.submit();
    }
}

function bulkUpdateStatus(status) {
    if (selectedApplications.length === 0) {
        alert('Please select applications first.');
        return;
    }
    
    if (confirm(`Are you sure you want to ${status} ${selectedApplications.length} application(s)?`)) {
        // Create and submit bulk status update form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/applications/bulk-status-update';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        const idsInput = document.createElement('input');
        idsInput.type = 'hidden';
        idsInput.name = 'application_ids';
        idsInput.value = JSON.stringify(selectedApplications);
        form.appendChild(idsInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function bulkAdmitCards() {
    if (selectedApplications.length === 0) {
        alert('Please select applications first.');
        return;
    }
    
    if (confirm(`Generate admit cards for ${selectedApplications.length} application(s)?`)) {
        document.getElementById('bulkApplicationIds').value = JSON.stringify(selectedApplications);
        document.getElementById('bulkActionsForm').submit();
    }
}

// Auto-submit form on filter changes (optional)
document.getElementById('status').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});

document.getElementById('class').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>
@endpush
