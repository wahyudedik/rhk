<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('file_pdf')->nullable()->after('file_dokumen');
            $table->string('file_docx')->nullable()->after('file_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['file_pdf', 'file_docx']);
        });
    }
};
