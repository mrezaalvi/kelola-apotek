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
        Schema::create('apotekers', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 32);
            $table->string('email', 64)->unique()->nullable();
            $table->string('stra_no')->unique();
            $table->date('stra_exp_date')->nullable();
            $table->string('stra_file')->nullable();
            $table->string('sipa_no')->unique();
            $table->date('sipa_exp_date')->nullable();
            $table->string('sipa_file')->nullable();
            $table->boolean('digunakan')->default(true);
            $table->foreignId('created_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'apoteker_created_by'
                )
                ->nullOnDelete();
            $table->foreignId('last_edited_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'apoteker_last_edited_by'
                )
                ->nullOnDelete();
            $table->timestamps();
            $table->unique(['nama', 'stra_no', 'sipa_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apotekers');
    }
};
