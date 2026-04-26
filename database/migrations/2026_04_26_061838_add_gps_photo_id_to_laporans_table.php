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
        Schema::table('laporans', function (Blueprint $table) {
            $table->foreignId('gps_photo_id')->nullable()->after('user_id')->constrained('gps_photos')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['gps_photo_id']);
            $table->dropColumn('gps_photo_id');
        });
    }
};
