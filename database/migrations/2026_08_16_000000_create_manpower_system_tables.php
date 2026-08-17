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
        Schema::enableForeignKeyConstraints();

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('status')->default('pending_review'); // pending_review, active, inactive, on_leave, terminated
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->date('joining_date')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->boolean('is_password_changed')->default(false);

            // Aadhaar and KYC Attributes
            $table->string('aadhaar_full_name')->nullable();
            $table->string('aadhaar_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('voter_id_number')->nullable();
            $table->string('prefix')->nullable();
            $table->string('father_name_aadhaar')->nullable();
            $table->string('mother_name_aadhaar')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->text('aadhaar_address')->nullable();
            $table->string('landmark')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('city')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('state')->nullable();
            $table->string('last_qualification')->nullable();
            $table->string('pass_out_year')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('email_id')->nullable();
            $table->string('old_uan_number')->nullable();
            $table->string('old_esic_number')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('client_name')->nullable();
            $table->string('work_location')->nullable();
            $table->string('designation')->nullable();
            $table->decimal('nth_salary', 10, 2)->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('offer_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('pdf_path');
            $table->timestamps();
        });

        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('month'); // e.g. "August 2026"
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->string('type'); // internal, external
            $table->string('pdf_path');
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('unread'); // unread, read, replied
            $table->timestamps();
        });

        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_content', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_content');
        Schema::dropIfExists('bulletins');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('offer_letters');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('designations');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('admins');
    }
};
