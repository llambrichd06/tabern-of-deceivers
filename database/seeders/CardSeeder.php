<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    public function run(): void
    {
        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $ranks = ['Ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King'];
        $cards = [];
        $id = 1; //id can't be 0

        // Generate 52 standard cards
        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $cards[] = [
                    'id'   => $id++,
                    'name' => "$rank of $suit",
                ];
            }
        }

        // Add 2 Jokers to complete the 54-card set
        $cards[] = ['id' => 53, 'name' => 'Black Joker'];
        $cards[] = ['id' => 54, 'name' => 'Red Joker'];

        DB::table('cards')->insert($cards);
    }
}
