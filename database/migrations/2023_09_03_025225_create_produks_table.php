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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 40)->nullable()->unique();
            $table->string('barcode', 50)->nullable()->unique();
            $table->string('nama', 150)->unique();
            $table->foreignId('satuan_id')->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('deskripsi')->nullable();
            $table->string('pabrik')->nullable();
            $table->string('kemasan')->nullable();
            $table->unsignedInteger('minimal_stok')->default(0);
            $table->unsignedDecimal('diskon', 5, 2)->default(0);
            $table->unsignedDecimal('harga_beli', 12, 2)->default(0);
            $table->unsignedDecimal('harga_jual', 12, 2)->default(0);
            $table->unsignedDecimal('margin_harga', 5, 2)->default(100);
            $table->boolean('digunakan')->default(true);
            $table->foreignId('created_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'produk_created_by'
                )
                ->nullOnDelete();
            $table->foreignId('last_edited_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'produk_last_edited_by'
                )
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
