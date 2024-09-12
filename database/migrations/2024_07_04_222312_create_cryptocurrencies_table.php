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
        Schema::create('cryptocurrencies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('symbol')->unique();
            $table->string('name');
            $table->string('name_full');
            $table->decimal('max_supply', 30, 8)->nullable();
            $table->string('icon_url')->nullable();
            $table->decimal('usd_rate', 30, 8)->nullable(); // current rate in USD
            $table->string('status')->default('inactive'); // e.g., active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cryptocurrencies');
    }
};
