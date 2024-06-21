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
        Schema::create('sandblastings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('name');
            $table->enum('shift',['1', '2', '3', 'NS']);
            $table->string('nomesin');
            $table->string('codeitem');
            $table->string('set');
            $table->integer('cavity_ok');
            $table->integer('cavity_ng')->nullable();
            $table->char('sandblasting')->nullable();
            $table->char('cuci')->nullable();
            $table->char('autosol')->nullable();
            $table->char('gerinda')->nullable();
            $table->char('oiling')->nullable();
            $table->time('cetakan_naik')->nullable();
            $table->time('cetakan_turun')->nullable();
            $table->string('rak');
            $table->string('mengetahui_spv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sandblastings');
    }
};
