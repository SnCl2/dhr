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
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'aadhaar_full_name')) {
                $table->string('aadhaar_full_name')->nullable();
            }
            if (!Schema::hasColumn('employees', 'aadhaar_number')) {
                $table->string('aadhaar_number')->nullable();
            }
            if (!Schema::hasColumn('employees', 'pan_number')) {
                $table->string('pan_number')->nullable();
            }
            if (!Schema::hasColumn('employees', 'voter_id_number')) {
                $table->string('voter_id_number')->nullable();
            }
            if (!Schema::hasColumn('employees', 'prefix')) {
                $table->string('prefix')->nullable();
            }
            if (!Schema::hasColumn('employees', 'father_name_aadhaar')) {
                $table->string('father_name_aadhaar')->nullable();
            }
            if (!Schema::hasColumn('employees', 'mother_name_aadhaar')) {
                $table->string('mother_name_aadhaar')->nullable();
            }
            if (!Schema::hasColumn('employees', 'gender')) {
                $table->string('gender')->nullable();
            }
            if (!Schema::hasColumn('employees', 'dob')) {
                $table->date('dob')->nullable();
            }
            if (!Schema::hasColumn('employees', 'mother_tongue')) {
                $table->string('mother_tongue')->nullable();
            }
            if (!Schema::hasColumn('employees', 'aadhaar_address')) {
                $table->text('aadhaar_address')->nullable();
            }
            if (!Schema::hasColumn('employees', 'landmark')) {
                $table->string('landmark')->nullable();
            }
            if (!Schema::hasColumn('employees', 'contact_number')) {
                $table->string('contact_number')->nullable();
            }
            if (!Schema::hasColumn('employees', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('employees', 'emergency_contact_number')) {
                $table->string('emergency_contact_number')->nullable();
            }
            if (!Schema::hasColumn('employees', 'pin_code')) {
                $table->string('pin_code')->nullable();
            }
            if (!Schema::hasColumn('employees', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('employees', 'last_qualification')) {
                $table->string('last_qualification')->nullable();
            }
            if (!Schema::hasColumn('employees', 'pass_out_year')) {
                $table->string('pass_out_year')->nullable();
            }
            if (!Schema::hasColumn('employees', 'marital_status')) {
                $table->string('marital_status')->nullable();
            }
            if (!Schema::hasColumn('employees', 'email_id')) {
                $table->string('email_id')->nullable();
            }
            if (!Schema::hasColumn('employees', 'old_uan_number')) {
                $table->string('old_uan_number')->nullable();
            }
            if (!Schema::hasColumn('employees', 'old_esic_number')) {
                $table->string('old_esic_number')->nullable();
            }
            if (!Schema::hasColumn('employees', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable();
            }
            if (!Schema::hasColumn('employees', 'ifsc_code')) {
                $table->string('ifsc_code')->nullable();
            }
            if (!Schema::hasColumn('employees', 'bank_name')) {
                $table->string('bank_name')->nullable();
            }
            if (!Schema::hasColumn('employees', 'client_name')) {
                $table->string('client_name')->nullable();
            }
            if (!Schema::hasColumn('employees', 'work_location')) {
                $table->string('work_location')->nullable();
            }
            if (!Schema::hasColumn('employees', 'designation')) {
                $table->string('designation')->nullable();
            }
            if (!Schema::hasColumn('employees', 'nth_salary')) {
                $table->decimal('nth_salary', 10, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'aadhaar_full_name',
                'aadhaar_number',
                'pan_number',
                'voter_id_number',
                'prefix',
                'father_name_aadhaar',
                'mother_name_aadhaar',
                'gender',
                'dob',
                'mother_tongue',
                'aadhaar_address',
                'landmark',
                'contact_number',
                'city',
                'emergency_contact_number',
                'pin_code',
                'state',
                'last_qualification',
                'pass_out_year',
                'marital_status',
                'email_id',
                'old_uan_number',
                'old_esic_number',
                'bank_account_number',
                'ifsc_code',
                'bank_name',
                'client_name',
                'work_location',
                'designation',
                'nth_salary',
            ]);
        });
    }
};
