<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('franchise_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->string('or_number');
            $table->decimal('amount', 8, 2);
            $table->date('cancellation_date');
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        // Add cancellation_date to operators table
        Schema::table('operators', function (Blueprint $table) {
            $table->date('franchise_cancelled_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('franchise_cancellations');
        
        Schema::table('operators', function (Blueprint $table) {
            $table->dropColumn('franchise_cancelled_at');
        });
    }
}; 