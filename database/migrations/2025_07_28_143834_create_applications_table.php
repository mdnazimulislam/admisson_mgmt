<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->string('student_name_en');
            $table->string('student_name_bn')->nullable();
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('religion');
            $table->string('class_applied');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('guardian_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();
            $table->text('address');
            $table->string('student_photo')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('guardian_nid')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('test_date')->nullable();
            $table->string('test_venue')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index(['application_id', 'status', 'class_applied']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
