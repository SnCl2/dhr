<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Remap 'RM HR Solutions Private Limited' (or ID 5) to ID 1
        $rmhr = DB::table('companies')
            ->where('name', 'RM HR Solutions Private Limited')
            ->orWhere('id', 5)
            ->first();

        if ($rmhr) {
            $oldId = $rmhr->id;

            // If a different company with ID 1 already exists, delete it first to avoid conflicts
            $existing1 = DB::table('companies')->where('id', 1)->first();
            if ($existing1 && $existing1->name !== $rmhr->name) {
                DB::table('companies')->where('id', 1)->delete();
                DB::table('employees')->where('company_id', 1)->update(['company_id' => null]);
            }

            // Perform updates
            DB::table('companies')->where('id', $oldId)->update(['id' => 1]);
            DB::table('employees')->where('company_id', $oldId)->update(['company_id' => 1]);
        }

        // 2. Remap 'FLIPKART' (or ID 4) to ID 2
        $flipkart = DB::table('companies')
            ->where('name', 'FLIPKART')
            ->orWhere('id', 4)
            ->first();

        if ($flipkart) {
            $oldId = $flipkart->id;

            // If a different company with ID 2 already exists, delete it first to avoid conflicts
            $existing2 = DB::table('companies')->where('id', 2)->first();
            if ($existing2 && $existing2->name !== $flipkart->name) {
                DB::table('companies')->where('id', 2)->delete();
                DB::table('employees')->where('company_id', 2)->update(['company_id' => null]);
            }

            // Perform updates
            DB::table('companies')->where('id', $oldId)->update(['id' => 2]);
            DB::table('employees')->where('company_id', $oldId)->update(['company_id' => 2]);
        }

        // 3. Reset the auto-increment sequence so new companies start from ID 3
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            DB::statement("UPDATE sqlite_sequence SET seq = 2 WHERE name = 'companies'");
        } else if ($driver === 'mysql') {
            DB::statement("ALTER TABLE companies AUTO_INCREMENT = 3");
        } else if ($driver === 'pgsql') {
            DB::statement("SELECT setval('companies_id_seq', 2)");
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op for structural data cleanup
    }
};
