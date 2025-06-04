<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('tracking_code')->unique()->after('status');
            $table->string('qr_code_path')->nullable()->after('tracking_code');
            $table->timestamp('last_status_update')->nullable()->after('notes');
            $table->json('status_history')->nullable()->after('last_status_update');
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['tracking_code', 'qr_code_path', 'last_status_update', 'status_history']);
        });
    }
}; 