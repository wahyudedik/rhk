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
        Schema::table('billing_plans', function (Blueprint $table) {
            $table->integer('batas_gps_photo_per_bulan')->nullable()->after('batas_laporan_per_bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_plans', function (Blueprint $table) {
            $table->dropColumn('batas_gps_photo_per_bulan');
        });
    }
};
