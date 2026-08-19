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
        Schema::table('companies', function (Blueprint $table) {
            // Earnings
            $table->decimal('basic', 10, 2)->default(0)->after('address');
            $table->decimal('hra', 10, 2)->default(0)->after('basic');
            $table->decimal('conveyance', 10, 2)->default(0)->after('hra');
            $table->decimal('medical_allowance', 10, 2)->default(0)->after('conveyance');
            $table->decimal('sp_allowance', 10, 2)->default(0)->after('medical_allowance');
            $table->decimal('gross_earning', 10, 2)->default(0)->after('sp_allowance');

            // Employer Contribution & CTC
            $table->decimal('bonus', 10, 2)->default(0)->after('gross_earning');
            $table->decimal('employer_pf', 10, 2)->default(0)->after('bonus');
            $table->decimal('employer_esic', 10, 2)->default(0)->after('employer_pf');
            $table->decimal('employer_lwf', 10, 2)->default(0)->after('employer_esic');
            $table->decimal('ctc', 10, 2)->default(0)->after('employer_lwf');

            // Deductions
            $table->decimal('employee_pf', 10, 2)->default(0)->after('ctc');
            $table->decimal('employee_esic', 10, 2)->default(0)->after('employee_pf');
            $table->decimal('employee_lwf', 10, 2)->default(0)->after('employee_esic');
            $table->decimal('professional_tax', 10, 2)->default(0)->after('employee_lwf');
            $table->decimal('total_deductions', 10, 2)->default(0)->after('professional_tax');

            // Net Salary
            $table->decimal('net_salary', 10, 2)->default(0)->after('total_deductions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'basic',
                'hra',
                'conveyance',
                'medical_allowance',
                'sp_allowance',
                'gross_earning',
                'bonus',
                'employer_pf',
                'employer_esic',
                'employer_lwf',
                'ctc',
                'employee_pf',
                'employee_esic',
                'employee_lwf',
                'professional_tax',
                'total_deductions',
                'net_salary',
            ]);
        });
    }
};
