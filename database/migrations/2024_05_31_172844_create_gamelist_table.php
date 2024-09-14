<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'gamelist';
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('id_hash')->unique();
            $table->string('category')->nullable(); // Allow category to be nullable
            $table->string('image', 300)->nullable();
            $table->string('image_portrait', 300)->nullable();
            $table->boolean('freerounds_supported')->default(false);
            $table->boolean('play_for_fun_supported')->default(true);
            $table->boolean('new')->default(false);
            $table->integer('index_rating')->default(255);
            $table->boolean('popular')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
