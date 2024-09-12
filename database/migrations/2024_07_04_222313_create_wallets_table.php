<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('uuid')->on('users')->onDelete('cascade');
            $table->uuid('currency_id');
            $table->foreign('currency_id')->references('uuid')->on('cryptocurrencies')->onDelete('cascade');
            $table->string('currency'); // e.g., BTC, ETH, USD, EUR
            $table->string('deposit_address')->unique()->nullable();
            $table->decimal('balance', 20, 8)->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
