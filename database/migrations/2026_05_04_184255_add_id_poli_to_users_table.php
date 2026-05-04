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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('id_poli')->nullable()->after('role');

        $table->foreign('id_poli')
              ->references('id')
              ->on('poli')
              ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['id_poli']);
        $table->dropColumn('id_poli');
    });
}
};
