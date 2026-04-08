<?php

namespace App\Http\Controllers\Api;

use App\Events\CardPlayed;
use App\Events\StartGame;
use App\Events\UpdateGameState;
use App\Events\GameWon;
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

    public function startGame(Request $request)
    {
        $room_id = $request->room_id;
        $gameForRoomExists = Game::where('room_id', $room_id)->where('is_finished', '0');

        if ($gameForRoomExists) return response()->json(['error' => 'This room has an unfinished game'], 409);

        $room = Room::where('room_id', $room_id)->first();
        $room->state = 'on_going';
        $room->save();

        $game = new Game;
        $game->room_id = $room_id;
        $game->is_finished = '0';
        $game->game_state = $this->createNewGameState($room_id);
        $game->save();

        broadcast(new StartGame($game));
        $this->updateGameState($game->id, $game->game_state);
    }


    private function createNewGameState($room_id) {
        //number of cards per player
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
        $gameState->last_player_turn = 0;
        $gameState->turn = 0;

        //setting the players in a random order
        $players = RoomUsers::select('user_id')->where('room_id')->inRandomOrder()->get();
        foreach ($players as $player) {
            array_push($gameState->players, $player->id);
        }
        
        //depending on player number, how many cards do we need to use in the game
        $amountOfCards = count($gameState->players)*$cardsPerPlayer;

        //grab the amount of cards needed randomly
        $cards = Card::inRandomOrder()->limit($amountOfCards)->get();

        $playerNum = 1;
        foreach ($cards as $card) {
            //update card count for current player
            $gameState->player_decks["player$playerNum"]['count'] += 1;
            //put the card in the player's deck
            array_push($gameState->player_decks["player$playerNum"]['cards'], $card);
            //if the amount of cards in the deck is equal (or greater, somehow) to the setted amount of cards per player, give cards to the next player
            if (count($gameState->player_decks["player$playerNum"]['cards']) >= $cardsPerPlayer) {
                $playerNum++;
            }
        }

        //encode the game state to json to save it in the DB
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

    private function validateAction() {

    }

    public function playCards(Request $request) {
        $gameState = $this->getDecodedGameStateById($request->gameId);
        $playerNum = $this->getLoggedPlayerNumFromState($gameState);
        $cardsPlayed = $request->idCards;
        $lastCalledRank = $request->calledRank;
        
        try {
            //Check if the game is finished
            $isGameFinished = $this->changeTurn($request->gameId, $playerNum);
            if ($isGameFinished) {
                return;
            }

            //if there was no called rank
            if ($gameState->pile['called_rank'] <= 0) {
               $gameState->pile['called_rank'] = $lastCalledRank;
            }

            $gameState->pile['last_played_cards'] = $cardsPlayed;

            //for each card played we do the same
            foreach ($cardsPlayed as $cardId) {
                //look for the card position in the array
                $cardPosition = array_search($cardId, $gameState->player_decks["player$playerNum"]['cards']);
                //remove the card from the player's deck
                unset($gameState->player_decks["player$playerNum"]['cards'][$cardPosition]);
                //add the cards to the pile
                array_push($gameState->pile['cards'], $cardId);
            }
            //change the card count of the player to the new card count
            $gameState->player_decks["player$playerNum"]['count'] = count($gameState->player_decks["player$playerNum"]['cards']);
            //change the pile count to the new count
            $gameState->pile['count'] = count($gameState->pile['cards']);
            
            $jsonGameState = json_encode($gameState);

            $this->updateGameState($request->gameId, $jsonGameState);
            broadcast(new CardPlayed(Game::find($request->gameId)));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function changeTurn($gameId, $playerNum) {
        $gameState = $this->getDecodedGameStateById($gameId);
        $lastPlayerTurn = $gameState->last_player_turn;

        if ($gameState->player_decks["player$lastPlayerTurn"]["count"] >= 0) {
            $this->gameWon($gameId);
            return true;
        }
        $gameState->last_player_turn = $gameState->current_player_turn;
        $gameState->current_player_turn = $playerNum;
        $gameState->turn = $gameState->turn + 1;
        return false;
    }
    
    private function getDecodedGameStateById($gameId) : stdClass {
        $gameState = Game::find($gameId)->game_state;
        $decodedGameState = json_decode($gameState);
        return $decodedGameState;
    }

    private function getLoggedPlayerNumFromState(stdClass $gameState) : int {
        $user = Auth::user();
        $playerNum = array_search($user->id, $gameState->players)+1;
        return $playerNum;
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
        $lastPlayedCards = $gameState->last_played_cards;
        $calledRank = $gameState->called_rank;


        $lastPlayer = $players[$gameState->last_player_turn-1];

        $lie = false;
        foreach ($lastPlayedCards as $card) {
            $currentCard = Card::find($card);
            $rankOfCard = $currentCard->rank;
            if ($rankOfCard != $calledRank && $rankOfCard != null) {
                $lie = true;
            }
        }

        if ($lie == true) {
            $gameState = $this->takeCards($gameId, $lastPlayer);
            $this->changeTurn($gameId, $user);
        } else {
            $gameState = $this->takeCards($gameId, $user);
            $this->changeTurn($gameId, $lastPlayer);
        } //encara falta la funcio per canviar de torn, un cop creat hem de possar que depenen de si es mentida o no començi el que no roba cartes d'aquesta funcio (si manteix el que ha delatat, si no el que della la veritat)
    }

    private function gameWon($gameId){
        $game = Game::find($gameId);
        $game->is_finished = '1';
        broadcast(new GameWon($game));
        $game->save();
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
