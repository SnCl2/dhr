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
        Schema::table('payslips', function (Blueprint $table) {
            $table->integer('working_days')->default(31);
            $table->integer('net_payable_days')->default(31);
            $table->integer('ot_days')->default(0);
            $table->string('pay_mode')->default('Bank Transfer');
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('medical_allowance', 10, 2)->default(0);
            $table->decimal('special_allowance', 10, 2)->default(0);
            $table->decimal('leave_encashment', 10, 2)->default(0);
            $table->decimal('ot_allowance', 10, 2)->default(0);
            $table->decimal('provident_fund', 10, 2)->default(0);
            $table->decimal('esic', 10, 2)->default(0);
            $table->decimal('professional_tax', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn([
                'working_days',
                'net_payable_days',
                'ot_days',
                'pay_mode',
                'hra',
                'medical_allowance',
                'special_allowance',
                'leave_encashment',
                'ot_allowance',
                'provident_fund',
                'esic',
                'professional_tax',
            ]);
        });
    }
};
