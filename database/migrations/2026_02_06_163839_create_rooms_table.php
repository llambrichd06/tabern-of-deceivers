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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_code');
            $table->unsignedBigInteger('player_1');
            $table->unsignedBigInteger('player_2');
            $table->unsignedBigInteger('player_3');
            $table->unsignedBigInteger('player_4');
            $table->unsignedBigInteger('player_5');
            $table->unsignedBigInteger('player_6');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
