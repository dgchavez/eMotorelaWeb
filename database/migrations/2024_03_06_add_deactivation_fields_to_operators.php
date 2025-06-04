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
        Schema::table('operators', function (Blueprint $table) {
            if (!Schema::hasColumn('operators', 'deactivation_date')) {
                $table->timestamp('deactivation_date')->nullable();
            }
            if (!Schema::hasColumn('operators', 'deactivation_reason')) {
                $table->string('deactivation_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operators', function (Blueprint $table) {
            $table->dropColumn(['deactivation_date', 'deactivation_reason']);
        });
    }
}; 