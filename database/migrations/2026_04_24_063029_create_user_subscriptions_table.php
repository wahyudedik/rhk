<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('billing_plan_id')->constrained('billing_plans')->cascadeOnDelete();
            $table->timestamp('mulai_at');
            $table->timestamp('berakhir_at');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->integer('laporan_digunakan')->default(0); // counter laporan bulan ini
            $table->timestamp('laporan_reset_at')->nullable(); // kapan counter direset
            $table->text('catatan')->nullable(); // catatan admin
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
