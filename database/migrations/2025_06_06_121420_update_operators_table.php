<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('operators', function (Blueprint $table) {
            // Make required fields not nullable
            $table->text('address')->nullable(false)->change();
            $table->string('contact_no', 20)->nullable(false)->change();
            
            // Add toda_id if it doesn't exist
            if (!Schema::hasColumn('operators', 'toda_id')) {
                $table->foreignId('toda_id')->after('email')->constrained('todas');
            }
            
            // Add status field if it doesn't exist
            if (!Schema::hasColumn('operators', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('toda_id');
            }
        });

        // Update motorcycles table to make required fields not nullable
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->string('motor_no', 50)->nullable(false)->change();
            $table->string('chassis_no', 50)->nullable(false)->change();
            $table->string('make', 100)->nullable(false)->change();
            $table->string('year_model', 10)->nullable(false)->change();
            $table->string('mv_file_no', 50)->nullable(false)->change();
            $table->string('plate_no', 20)->nullable(false)->change();
            $table->string('color', 50)->nullable(false)->change();
            $table->date('registration_date')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('operators', function (Blueprint $table) {
            $table->text('address')->nullable()->change();
            $table->string('contact_no', 20)->nullable()->change();
            
            if (Schema::hasColumn('operators', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('operators', 'toda_id')) {
                $table->dropForeign(['toda_id']);
                $table->dropColumn('toda_id');
            }
        });

        Schema::table('motorcycles', function (Blueprint $table) {
            $table->string('motor_no', 50)->nullable()->change();
            $table->string('chassis_no', 50)->nullable()->change();
            $table->string('make', 100)->nullable()->change();
            $table->string('year_model', 10)->nullable()->change();
            $table->string('mv_file_no', 50)->nullable()->change();
            $table->string('plate_no', 20)->nullable()->change();
            $table->string('color', 50)->nullable()->change();
            $table->date('registration_date')->nullable()->change();
        });
    }
}; 