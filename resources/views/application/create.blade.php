@extends('layouts.app')

@section('title', 'Apply for Admission - Boni School')

@push('styles')
<style>
    .form-step {
        display: none;
    }
    .form-step.active {
        display: block;
    }
    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }
    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 1rem;
        font-weight: bold;
        position: relative;
    }
    .step.active {
        background: #007bff;
        color: white;
    }
    .step.completed {
        background: #28a745;
        color: white;
    }
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 100%;
        width: 2rem;
        height: 2px;
        background: #e9ecef;
        z-index: -1;
    }
    .step.completed:not(:last-child)::after {
        background: #28a745;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Admission Application Form
                    </h3>
                    <p class="mb-0 mt-2">Fill in all required information carefully</p>
                </div>
                
                <div class="card-body">
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active" id="step-1">1</div>
                        <div class="step" id="step-2">2</div>
                        <div class="step" id="step-3">3</div>
                        <div class="step" id="step-4">4</div>
                    </div>

                    <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" id="applicationForm">
                        @csrf

                        <!-- Step 1: Student Information -->
                        <div class="form-step active" id="step-content-1">
                            <h4 class="mb-4">
                                <i class="fas fa-user me-2"></i>Student Information
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="student_name_en" class="form-label">Student Name (English) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('student_name_en') is-invalid @enderror" 
                                           id="student_name_en" name="student_name_en" value="{{ old('student_name_en') }}" required>
                                    @error('student_name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="student_name_bn" class="form-label">Student Name (Bangla)</label>
                                    <input type="text" class="form-control @error('student_name_bn') is-invalid @enderror" 
                                           id="student_name_bn" name="student_name_bn" value="{{ old('student_name_bn') }}">
                                    @error('student_name_bn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="birth_date" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="religion" class="form-label">Religion <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('religion') is-invalid @enderror" 
                                           id="religion" name="religion" value="{{ old('religion') }}" required>
                                    @error('religion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="class_applied" class="form-label">Class Applied For <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_applied') is-invalid @enderror" id="class_applied" name="class_applied" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class }}" {{ old('class_applied') == $class ? 'selected' : '' }}>
                                                {{ $class }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_applied')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Parent/Guardian Information -->
                        <div class="form-step" id="step-content-2">
                            <h4 class="mb-4">
                                <i class="fas fa-users me-2"></i>Parent/Guardian Information
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="father_name" class="form-label">Father's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror" 
                                           id="father_name" name="father_name" value="{{ old('father_name') }}" required>
                                    @error('father_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror" 
                                           id="mother_name" name="mother_name" value="{{ old('mother_name') }}" required>
                                    @error('mother_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="father_occupation" class="form-label">Father's Occupation</label>
                                    <input type="text" class="form-control @error('father_occupation') is-invalid @enderror" 
                                           id="father_occupation" name="father_occupation" value="{{ old('father_occupation') }}">
                                    @error('father_occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="mother_occupation" class="form-label">Mother's Occupation</label>
                                    <input type="text" class="form-control @error('mother_occupation') is-invalid @enderror" 
                                           id="mother_occupation" name="mother_occupation" value="{{ old('mother_occupation') }}">
                                    @error('mother_occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="guardian_name" class="form-label">Guardian Name (if different from parents)</label>
                                    <input type="text" class="form-control @error('guardian_name') is-invalid @enderror" 
                                           id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}">
                                    @error('guardian_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Contact Information -->
                        <div class="form-step" id="step-content-3">
                            <h4 class="mb-4">
                                <i class="fas fa-phone me-2"></i>Contact Information
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" required>
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="contact_email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Document Upload -->
                        <div class="form-step" id="step-content-4">
                            <h4 class="mb-4">
                                <i class="fas fa-upload me-2"></i>Document Upload
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="student_photo" class="form-label">Student Photograph</label>
                                    <input type="file" class="form-control @error('student_photo') is-invalid @enderror" 
                                           id="student_photo" name="student_photo" accept="image/jpeg,image/png,image/jpg">
                                    <small class="form-text text-muted">JPG, PNG files only. Maximum size: 2MB</small>
                                    @error('student_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="birth_certificate" class="form-label">Birth Certificate</label>
                                    <input type="file" class="form-control @error('birth_certificate') is-invalid @enderror" 
                                           id="birth_certificate" name="birth_certificate" accept=".pdf,image/jpeg,image/png,image/jpg">
                                    <small class="form-text text-muted">PDF, JPG, PNG files only. Maximum size: 5MB</small>
                                    @error('birth_certificate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="guardian_nid" class="form-label">Guardian NID Copy (Optional)</label>
                                    <input type="file" class="form-control @error('guardian_nid') is-invalid @enderror" 
                                           id="guardian_nid" name="guardian_nid" accept=".pdf,image/jpeg,image/png,image/jpg">
                                    <small class="form-text text-muted">PDF, JPG, PNG files only. Maximum size: 5MB</small>
                                    @error('guardian_nid')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms_agree" required>
                                        <label class="form-check-label" for="terms_agree">
                                            I agree that all information provided is accurate and complete. I understand that any false information may result in rejection of the application.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                                Next<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            
                            <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                <i class="fas fa-paper-plane me-2"></i>Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 4;

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(function(element) {
            element.classList.remove('active');
        });
        
        // Show current step
        document.getElementById('step-content-' + step).classList.add('active');
        
        // Update step indicators
        document.querySelectorAll('.step').forEach(function(element, index) {
            element.classList.remove('active', 'completed');
            if (index + 1 < step) {
                element.classList.add('completed');
            } else if (index + 1 === step) {
                element.classList.add('active');
            }
        });
        
        // Update navigation buttons
        document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'inline-block';
        document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'inline-block';
        document.getElementById('submitBtn').style.display = step === totalSteps ? 'inline-block' : 'none';
    }

    function validateStep(step) {
        let isValid = true;
        const stepContent = document.getElementById('step-content-' + step);
        const requiredFields = stepContent.querySelectorAll('[required]');
        
        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Special validation for step 4 (checkbox)
        if (step === 4) {
            const termsCheckbox = document.getElementById('terms_agree');
            if (!termsCheckbox.checked) {
                termsCheckbox.classList.add('is-invalid');
                isValid = false;
            } else {
                termsCheckbox.classList.remove('is-invalid');
            }
        }
        
        return isValid;
    }

    function changeStep(direction) {
        if (direction === 1 && !validateStep(currentStep)) {
            Swal.fire({
                title: 'Incomplete Information',
                text: 'Please fill in all required fields before proceeding.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        currentStep += direction;
        
        if (currentStep < 1) currentStep = 1;
        if (currentStep > totalSteps) currentStep = totalSteps;
        
        showStep(currentStep);
    }

    // Initialize form
    document.addEventListener('DOMContentLoaded', function() {
        showStep(1);
        
        // Add file size validation
        document.querySelectorAll('input[type="file"]').forEach(function(input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const maxSize = this.id === 'student_photo' ? 2 * 1024 * 1024 : 5 * 1024 * 1024; // 2MB for photo, 5MB for documents
                    
                    if (file.size > maxSize) {
                        Swal.fire({
                            title: 'File Too Large',
                            text: `File size should be less than ${maxSize / (1024 * 1024)}MB`,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        this.value = '';
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
