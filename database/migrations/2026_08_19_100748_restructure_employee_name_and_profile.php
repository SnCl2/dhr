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
            if (!Schema::hasColumn('employees', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('status');
            }
            if (Schema::hasColumn('employees', 'first_name')) {
                $table->string('first_name')->nullable()->change();
            }
            if (Schema::hasColumn('employees', 'last_name')) {
                $table->string('last_name')->nullable()->change();
            }
            if (Schema::hasColumn('employees', 'client_name')) {
                $table->string('client_name')->nullable()->change();
            }
            if (Schema::hasColumn('employees', 'designation')) {
                $table->string('designation')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'profile_image')) {
                $table->dropColumn('profile_image');
            }
        });
    }
};
