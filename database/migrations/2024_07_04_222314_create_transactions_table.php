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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('uuid')->on('users')->onDelete('cascade');
            $table->uuid('wallet_id');
            $table->foreign('wallet_id')->references('uuid')->on('wallets')->onDelete('cascade');
            $table->uuid('currency_id');
            $table->foreign('currency_id')->references('uuid')->on('cryptocurrencies')->onDelete('cascade');
            $table->decimal('amount', 20, 8);
            $table->string('transaction_type'); // deposit, withdrawal, exchange, game_win, transfer
            $table->string('status'); // pending, completed, failed
            $table->decimal('exchange_rate', 20, 8)->nullable(); // Exchange rate at the time of transaction
            $table->string('deposit_address')->nullable(); // Cryptocurrency deposit address
            $table->string('deposited_from')->nullable(); // External wallet address or source
            $table->uuid('game_id')->nullable();
            $table->foreign('game_id')->references('uuid')->on('games')->onDelete('cascade'); // Game ID in case of game win
            $table->uuid('transfer_to')->nullable();
            $table->foreign('transfer_to')->references('uuid')->on('users')->onDelete('cascade'); // User ID to whom the amount was transferred
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
