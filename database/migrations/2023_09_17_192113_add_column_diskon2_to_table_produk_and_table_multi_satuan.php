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
        Schema::table('produks', function (Blueprint $table) {
            $table->unsignedDecimal('diskon2', 5, 2)->default(0)->after('diskon');
        });
        Schema::table('multi_satuans', function (Blueprint $table) {
            $table->unsignedDecimal('diskon2', 5, 2)->default(0)->after('diskon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('table_produk_and_table_multi_satuan', function (Blueprint $table) {
        //     //
        // });
    }
};
