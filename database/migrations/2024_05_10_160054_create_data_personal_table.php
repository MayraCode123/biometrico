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
        Schema::create('data_personal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_id_biometrico');
            $table->foreign('data_id_biometrico')->references('id_biometrico')->on('data')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_personal');
    }
};
