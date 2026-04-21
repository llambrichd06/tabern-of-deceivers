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
        Schema::create('game_leaderboard', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');

            $table->unsignedBigInteger('leaderboard_id');
            $table->foreign('leaderboard_id')->references('id')->on('leaderboards')->onDelete('cascade');

            $table->integer('position');
            $table->integer('points_gained');

            $table->primary(['game_id','leaderboard_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_leaderboard');
    }
};
