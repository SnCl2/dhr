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

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('activity_type'); // employee_import, bulk_offer_letter_upload, bulk_payslip_upload, bulk_payslip_generate
            $table->string('performed_by_type')->nullable(); // admin, staff
            $table->unsignedBigInteger('performed_by_id')->nullable();
            $table->string('performed_by_name')->nullable();
            $table->string('filename')->nullable();
            $table->integer('success_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->string('failed_csv_path')->nullable();
            $table->text('details')->nullable(); // JSON/serialized text details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
