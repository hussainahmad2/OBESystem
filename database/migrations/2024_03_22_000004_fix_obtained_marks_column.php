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
        Schema::table('assessment_clo_details', function (Blueprint $table) {
            // First drop the column if it exists with a different name
            if (Schema::hasColumn('assessment_clo_details', 'obtained_marks')) {
                $table->dropColumn('obtained_marks');
            }
            if (Schema::hasColumn('assessment_clo_details', 'marks')) {
                $table->dropColumn('marks');
            }
            
            // Add the column with the correct name and default value
            $table->decimal('obtained_marks', 8, 2)->default(0)->after('total_marks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_clo_details', function (Blueprint $table) {
            if (Schema::hasColumn('assessment_clo_details', 'obtained_marks')) {
                $table->dropColumn('obtained_marks');
            }
        });
    }
}; 