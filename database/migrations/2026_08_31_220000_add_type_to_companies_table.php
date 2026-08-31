<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('type')->default('employee')->after('address');
        });

        // Set company ID 1 to 'staff' if exists, and others to 'employee'
        DB::table('companies')->where('id', 1)->update(['type' => 'staff']);
        DB::table('companies')->where('id', '!=', 1)->update(['type' => 'employee']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
