<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->decimal('harga', 12, 2);
            $table->integer('durasi_hari')->default(30); // 30 hari = 1 bulan
            $table->integer('batas_laporan_per_bulan')->nullable(); // null = unlimited
            $table->json('fitur')->nullable(); // array fitur
            $table->boolean('is_trial')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_plans');
    }
};
