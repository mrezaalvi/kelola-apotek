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
        Schema::table('multi_satuans', function (Blueprint $table) {
            $table->foreignId('created_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'multi_satuan_created_by'
                )
                ->nullOnDelete();
            $table->foreignId('last_edited_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'multi_satuan_last_edited_by'
                )
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('multi_satuans', function (Blueprint $table) {
            //
        });
    }
};
