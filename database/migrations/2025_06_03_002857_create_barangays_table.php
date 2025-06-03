<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        // Add barangay_id to existing tables
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('barangay_id')->nullable()->after('email')
                  ->constrained()->nullOnDelete();
        });

        Schema::table('operators', function (Blueprint $table) {
            $table->foreignId('barangay_id')->nullable()->after('email')
                  ->constrained()->nullOnDelete();
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->foreignId('barangay_id')->nullable()->after('address')
                  ->constrained()->nullOnDelete();
        });
    }

    public function down()
    {
        // Remove foreign keys first
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['barangay_id']);
            $table->dropColumn('barangay_id');
        });

        Schema::table('operators', function (Blueprint $table) {
            $table->dropForeign(['barangay_id']);
            $table->dropColumn('barangay_id');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['barangay_id']);
            $table->dropColumn('barangay_id');
        });

        // Drop the main table
        Schema::dropIfExists('barangays');
    }
};