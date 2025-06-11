<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['operator_id']);
            $table->dropColumn('operator_id');
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->foreignId('operator_id')->after('id')->constrained('operators')->onDelete('cascade');
        });
    }
}; 