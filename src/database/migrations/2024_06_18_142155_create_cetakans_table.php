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
        Schema::create('cetakans', function (Blueprint $table) {
            $table->id();
            $table->string('codeitem');
            $table->string('partname')->nullable();
            $table->string('partnumber');
            $table->integer('cavity')->nullable();
            $table->string('set')->nullable();
            $table->integer('rak')->nullable(); 
            $table->integer('norak')->nullable();
            $table->string('posisi_tooling');
            $table->enum('keterangan', ['Aktif', 'Tidak Aktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cetakans');
    }
};
