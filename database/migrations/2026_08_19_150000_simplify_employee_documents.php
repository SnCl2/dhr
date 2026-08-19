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
            // Drop old document columns if they exist
            $columns = [
                'doc_aadhaar_front',
                'doc_aadhaar_back',
                'doc_pan',
                'doc_voter_front',
                'doc_voter_back',
                'doc_qualification_marksheet',
                'doc_qualification_certificate',
                'doc_photo',
                'doc_bank_passbook',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('employees', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add new unified document column and profile image column
            if (!Schema::hasColumn('employees', 'employee_document')) {
                $table->string('employee_document')->nullable();
            }
            if (!Schema::hasColumn('employees', 'profile_image')) {
                $table->string('profile_image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Add back dropped columns
            $table->string('doc_aadhaar_front')->nullable();
            $table->string('doc_aadhaar_back')->nullable();
            $table->string('doc_pan')->nullable();
            $table->string('doc_voter_front')->nullable();
            $table->string('doc_voter_back')->nullable();
            $table->string('doc_qualification_marksheet')->nullable();
            $table->string('doc_qualification_certificate')->nullable();
            $table->string('doc_photo')->nullable();
            $table->string('doc_bank_passbook')->nullable();

            // Drop columns
            if (Schema::hasColumn('employees', 'employee_document')) {
                $table->dropColumn('employee_document');
            }
            if (Schema::hasColumn('employees', 'profile_image')) {
                $table->dropColumn('profile_image');
            }
        });
    }
};
