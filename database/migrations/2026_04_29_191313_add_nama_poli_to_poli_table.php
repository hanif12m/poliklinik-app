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
        Schema::table('poli', function (Blueprint $table) {
            $table->string('nama_poli')->after('id');
            $table->text('keterangan')->nullable()->after('nama_poli');
        });
    }

    public function down(): void
    {
        Schema::table('poli', function (Blueprint $table) {
            $table->dropColumn(['nama_poli', 'keterangan']);
        });
    }
};
