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
        Schema::create('cryptocurrency_blockchains', function (Blueprint $table) {
            $table->id();
            $table->string('blockchain'); // e.g., bitcoin, ethereum
            $table->string('network')->default('mainnet'); // e.g., mainnet, testnet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cryptocurrency_blockchains');
    }
};
