<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\RoomUsers;
use Illuminate\Http\Request;
use stdClass;

class GameController extends Controller
{
    public function getUserGameStateById(Request $request) {
        $game = Game::find($request->id);

        if (!$game) return response()->json(['error' => 'Game not found'], 404);
    

        return response()->json(['game_state' => $game->game_state]);
    }

    public function saveGameStateById(Request $request) {
        $game = Game::find($request->id);
        

        
        return response()->json(['game_state' => $game->game_state]);
    }



    public function create(Request $request) {
        $game = new Game;
        $game->room_id = $request->room_id;
        $game->is_finished = 0;
        $game->game_state = $this->createNewGameState($request->room_id);
        $game->save();

        return response()->json(['game_state' => $game->game_state]);
    }
    

    private function createNewGameState($room_id) {
        $cardsPerPlayer = 6;
        $gameState = new stdClass;

        $gameState->pile = [
            'count' => 0,
            'cards' => [],
            'last_played_cards' => [],
            'called_rank' => '0',
        ];

        $gameState->player_decks = [
            'player1' => ['count' => 0, 'cards' => []],
            'player2' => ['count' => 0, 'cards' => []],
            'player3' => ['count' => 0, 'cards' => []],
            'player4' => ['count' => 0, 'cards' => []],
            'player5' => ['count' => 0, 'cards' => []],
            'player6' => ['count' => 0, 'cards' => []],
        ];
    
        $gameState->players = [];
        
        
        $gameState->current_player_turn = 0;
        $gameState->turn = 0;

        $players = RoomUsers::select('user_id')->where('room_id')->inRandomOrder()->get();
        foreach ($players as $player) {
            array_push($gameState->players, $player->id);
        }

        $amountOfCards = count($gameState->players)*$cardsPerPlayer;

        $cards = Card::inRandomOrder()->limit($amountOfCards)->get();

        $playerNum = 0;
        foreach ($cards as $card) {
            $gameState->player_decks["player$playerNum"]['count'] += 1;
            array_push($gameState->player_decks["player$playerNum"]['cards'], $card);
            if (count($gameState->player_decks["player$playerNum"]['cards']) >= $cardsPerPlayer) {
                $playerNum++;
            }
        }

        $jsonGameState = json_encode($gameState);

        return $jsonGameState;
        }
}

/*
{
    pile:{
        count:3,
        cards:[1,3,5,7],
        last_played_cards:[5,7],
        last_called_rank:[10],
    },

    player_decks:[
        'player1' => ['count' => 0, 'cards' => []],
        'player2' => ['count' => 0, 'cards' => []],
        'player3' => ['count' => 0, 'cards' => []],
        'player4' => ['count' => 0, 'cards' => []],
        'player5' => ['count' => 0, 'cards' => []],
        'player6' => ['count' => 0, 'cards' => []],
    ],

    players:[5,3,8,2,1,10],
    

    current_player_turn: 2,
}
*/
