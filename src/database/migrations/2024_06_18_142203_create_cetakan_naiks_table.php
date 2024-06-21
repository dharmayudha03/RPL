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
        Schema::create('cetakan_naiks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('shift');
            $table->time('jam')->nullable();
            $table->string('name');
            $table->string('nomesin')->nullable();
            $table->string('codeitem');
            $table->string('set');
            $table->integer('rak');
            $table->integer('jumlah_cavity');
            $table->char('cavity');
            $table->char('guidepen');
            $table->char('busing');
            $table->char('baut-mur');
            $table->char('core');
            $table->char('piston');
            $table->char('pot');
            $table->char('pl');
            $table->enum('kesimpulan', ['OK', 'NG']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cetakan_naiks');
    }
};
