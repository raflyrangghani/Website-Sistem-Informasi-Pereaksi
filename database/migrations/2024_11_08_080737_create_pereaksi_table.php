<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pereaksi', function (Blueprint $table) {
            $table->string('KODE', 50)->primary();
            $table->string('ITEM', 100)->nullable();
            $table->string('TYPE', 50)->nullable();
            $table->integer('Stock');
            $table->string('Status', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pereaksi');
    }
};
