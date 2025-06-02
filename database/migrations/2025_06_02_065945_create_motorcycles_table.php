<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->string('mtop_no', 50)->unique();
            $table->string('motor_no', 50)->nullable();
            $table->string('chassis_no', 50)->nullable();
            $table->string('make', 100)->nullable();
            $table->string('year_model', 10)->nullable();
            $table->string('mv_file_no', 50)->nullable();
            $table->string('plate_no', 20)->nullable();
            $table->string('color', 50)->nullable();
            $table->date('registration_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('motorcycles');
    }
};