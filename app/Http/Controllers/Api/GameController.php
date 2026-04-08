<?php

namespace App\Http\Controllers\Api;

use App\Events\StartGame;
use App\Events\UpdateGameState;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\Room;
use App\Models\RoomUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class GameController extends Controller
{
    public function getUserGameStateById(Request $request)
    {
        $game = Game::find($request->game_id);

        if (!$game) return response()->json(['error' => 'Game not found'], 404);

        return response()->json(['game_state' => $game->game_state]); //WE NEED TO EDIT THE DATA SENT TO THE USER SO ITS FOR THAT USER SPECIFICALLY
    }

    public function saveGameStateById(Request $request)
    {
        $game = Game::find($request->id);



        return response()->json(['game_state' => $game->game_state]);
    }



    public function create(Request $request)
    {
        $room_id = $request->room_id;
        $gameForRoomExists = Game::where('room_id', $room_id)->where('is_finished', '0');

        if ($gameForRoomExists) return response()->json(['error' => 'This room has an unfinished game']);

        $room = Room::where('room_id', $room_id)->first();
        $room->state = 'on_going';
        $room->save();

        $game = new Game;
        $game->room_id = $room_id;
        $game->is_finished = '0';
        $game->game_state = $this->createNewGameState($room_id);
        $game->save();



        broadcast(new StartGame($game));
        // return response()->json(['game_state' => $game->game_state]);
    }



    private function createNewGameState($room_id)
    {
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

        $amountOfCards = count($gameState->players) * $cardsPerPlayer;

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

    private function updateGameState($gameId, $gameState) {
        $game = Game::find($gameId);
        if (!$game) {
            $game->gameState = $gameState;
            $game->save();
            broadcast(new UpdateGameState($gameId, $gameState));
            return "Succes in updating the gameState";
        } else {
            return "Unsuccesfull to update gameState";
        }
    }

    private function validAction() {

    }
    
    private function getDecodedGameStateById($gameId) {
        $gameState = Game::find($gameId)->game_state;
        $decodedGameState = json_decode($gameState);
        return $decodedGameState;
    }

    private function takeCards($gameId, $idPlayerTaker) {
        $gameState = $this->getDecodedGameStateById($gameId);

        $players = $gameState->players;

        $PositionPlayerTaker = array_search($idPlayerTaker, $players); //rep la posicio del jugador amb id idPlayerTaker de la array players
        $playerNum = $PositionPlayerTaker + 1;
        $playerTaker = $gameState->player_decks["player$playerNum"];

        $playerTakerCardsCount = $playerTaker->count;
        $playerTakerCards = $playerTaker->cards;

        $countCardsInPile = $gameState->pile->count;
        $CardsInPile = $gameState->pile->cards;

        $newPlayerTakerCardsCount = $countCardsInPile + $playerTakerCardsCount;
        $newPlayerTakerCards = array_merge($CardsInPile, $playerTakerCards);

        $gameState->player_decks["player$playerNum"]->count[$newPlayerTakerCardsCount];
        $gameState->player_decks["player$playerNum"]->cards[$newPlayerTakerCards];

        $gameState->pile = [ //reset de la pila
            'count' => 0,
            'cards' => [],
            'last_played_cards' => [],
            'called_rank' => '0',
        ];

        $jsonGameState = json_encode($gameState);

        $result = $this->updateGameState($gameId, $jsonGameState);

        if ($result == "Succes in updating the gameState") {
            return "Taken cards succesfuly";
        } else {
            return "Error at tring to take cards";
        }
        
    }

    public function callLie($gameId) {
        $user = Auth::user();
        $gameState = $this->getDecodedGameStateById($gameId);
        

        $players = $gameState->players;
        $totalPlayers = count($players);
        $currentPlayerTurn = $gameState->current_player_turn;
        $lastPlayedCards = $gameState->last_played_cards;
        $calledRank = $gameState->called_rank;

        $positionOfCurrentPlayer = array_search($currentPlayerTurn, $players);
        if ($positionOfCurrentPlayer == 0) {
            $positionOfLastPlayerTurn = $totalPlayers - 1;
        }
        $positionOfLastPlayerTurn = $positionOfCurrentPlayer - 1;
        $lastPlayer = $players[$positionOfLastPlayerTurn];

        $lie = false;
        foreach ($lastPlayedCards as $card) {
            $currentCard = Card::find($card);
            $rankOfCard = $currentCard->rank;
            if ($rankOfCard != $calledRank) {
                $lie = true;
            }
        }

        if ($lie == false) {
            $gameState = $this->takeCards($gameId, $user);
        } else {
            $gameState = $this->takeCards($gameId, $lastPlayer);
        } //encara falta la funcio per canviar de torn, un cop creat hem de possar que depenen de si es mentida o no començi el que no roba cartes d'aquesta funcio (si manteix el que ha delatat, si no el que della la veritat)
    }
    /*
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
}
