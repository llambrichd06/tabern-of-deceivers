<?php

namespace App\Http\Controllers\Api;

use App\Events\CardPlayed;
use App\Events\StartGame;
use App\Events\UpdateGameState;
use App\Events\GameWon;
use App\Events\LieCalled;
use App\Events\TakenCards;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\Leaderboard;
use App\Models\Room;
use App\Models\RoomUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use stdClass;

class GameController extends Controller
{
    public function index() {
        $this->authorize('game-list');
    }
    
    public function show() {
        $this->authorize('game-list');
    }
    
    public function store() {
        $this->authorize('game-create');
    }
    
    public function update() {
        $this->authorize('game-edit');
    }
    
    public function delete() {
        $this->authorize('game-delete');
    }
    



    public function getUserGameStateById(Game $game)
    {
        if (!$game->game_state) return response()->json(['error' => 'Game not found'], 404);

        //we need to send the whole game object, but edit the gamestate so that its only for the logged user
        return response()->json(['game_state' => $game->game_state]); //WE NEED TO EDIT THE DATA SENT TO THE USER SO ITS FOR THAT USER SPECIFICALLY
    }

    public function getGame(Game $game) {
        if (!$game->id || $game->is_finished) return response()->json(['error' => 'Game not found'], 404);
        
        $game->game_state = json_decode($game->game_state);
        // $myPlayerNum = $this->getLoggedPlayerNumFromState($game->game_state);
        
        // foreach ($game->game_state->player_decks as $playerNum => $playerDeck) {
        //     if ($playerNum == $myPlayerNum) continue;
        // }
        return response()->json(['game' => $game]);
    }

    public function startGame(Request $request)
    {
        $roomId = $request->room_id;
        $gameForRoomExists = Game::where('room_id', $roomId)->where('is_finished', '0')->first();

        if ($gameForRoomExists) return response()->json(['error' => 'This room has an unfinished game'], 409);

        $room = Room::where('id', $roomId)->first();

        if ($room->players()->count() < 2) return response()->json(['error' => 'You need at least 2 players to start a game!'], 409);
        
        $room->state = 'on_going';
        $room->save();

        $game = new Game;
        $game->room_id = $roomId;
        $game->is_finished = '0';
        $game->game_state = $this->createNewGameState($roomId);
        $game->save();

        broadcast(new StartGame($game));
        $this->updateGameState($game->id, $game->game_state);
    }

    public function playCards(Request $request) {
        broadcast(new CardPlayed(Game::find($request->gameId)));
        $gameState = $this->getDecodedGameStateById($request->gameId);
        $playerNum = $this->getLoggedPlayerNumFromState($gameState); //player playing the cards
        $playerGrabbed = 'player'.$playerNum;


        /**
         * we are getting the ids and then turning the array of ids into an array of cards
         */
        $cardsPlayedIds = $request->idCards;
        $lastCalledRank = $request?->calledRank;

        $cardsPlayed = Card::whereIn('id', $cardsPlayedIds)->get();

        try {
            $nextPlayerNum = isset($gameState->players[$playerNum]) ? $playerNum+1 : 1;

            $isGameFinished = $this->changeTurnTo($request->gameId, $nextPlayerNum);
            if ($isGameFinished) {
                return;
            }
            $gameState = $this->getDecodedGameStateById($request->gameId); //Grab the game state again after changin turn

            //if there was no called rank
            if ($gameState->pile->called_rank <= 0) {
               $gameState->pile->called_rank = $lastCalledRank;
            }

            $gameState->pile->last_played_cards = $cardsPlayed;
            $gameState->pile->last_played_cards_count = count($cardsPlayed);
            
            foreach ($cardsPlayed as $card) { 
                // Create an array of just the IDs from the player's hand (yes array_column can grab the id's from card objects, so smart it gave me a headache)
                $playerCardIds = array_column($gameState->player_decks->{$playerGrabbed}->cards, 'id');

                // Search for the current card's ID in that list
                $cardPosition = array_search($card->id, $playerCardIds);
                if ($cardPosition !== false) {
                    // remove the card from the player's deck
                    // Log::info(json_encode($gameState->player_decks->{$playerGrabbed}->cards[$cardPosition], JSON_PRETTY_PRINT) );
                    unset($gameState->player_decks->{$playerGrabbed}->cards[$cardPosition]);

                    // add the card to the pile
                    array_push($gameState->pile->cards, $card);

                    //re index array cause unset leaves empty spots and it breaks it
                    $gameState->player_decks->{$playerGrabbed}->cards = array_values($gameState->player_decks->{$playerGrabbed}->cards);
                }
            }
            // re-index the array after using unset
            $gameState->player_decks->{$playerGrabbed}->cards = array_values($gameState->player_decks->{$playerGrabbed}->cards);
            
            //change the card count of the player to the new card count
            $gameState->player_decks->{$playerGrabbed}->count = count($gameState->player_decks->{$playerGrabbed}->cards);
            //change the pile count to the new count
            $gameState->pile->count = count($gameState->pile->cards);
            
            $jsonGameState = json_encode($gameState);

            $this->updateGameState($request->gameId, $jsonGameState);
            return response()->json('success');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function callLie(Request $request) {
        $gameId = $request->gameId;

        broadcast(new LieCalled(Game::find($gameId)));

        $gameState = $this->getDecodedGameStateById($gameId);
        $callerNum = $this->getLoggedPlayerNumFromState($gameState);

        $players = $gameState->players;
        $lastPlayedCards = $gameState->pile->last_played_cards;
        if (empty($lastPlayedCards)) {
            return response()->json(['error' => 'A lie was called already!'], 400);
        }
        $calledRank = $gameState->pile->called_rank;

        $lastPlayer = $gameState->last_player_turn;
        Log::info($lastPlayer);
        Log::info($callerNum);
        $lie = false;
        foreach ($lastPlayedCards as $card) {
            $currentCard = Card::find($card->id);
            $rankOfCard = $currentCard->rank;
            if ($rankOfCard != $calledRank && !is_null($rankOfCard)) {
                $lie = true;
            }
        }

        if ($lie == true) {
            $this->changeTurnTo($gameId, $callerNum);
            $result = $this->takeCards($gameId, $lastPlayer);
            $resultMessage = 'It was a lie!';
        } else {
            $this->changeTurnTo($gameId, $lastPlayer, false);
            $result = $this->takeCards($gameId, $callerNum);
            $resultMessage = 'It was the truth!';
        } 
        broadcast(new TakenCards(Game::find($gameId), $resultMessage));
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Something went wrong when calling a lie!'], 400);
        }
    }

    //PRIVATE FUNCTIONS----------------------------------------------------

    private function createNewGameState($roomId) {
        //number of cards per player
        $cardsPerPlayer = 6; //WE ARE ACTUALLY GONNA SHUFFLE AS MANY CARDS IN THE DECK BETWEEN ALL PLAYERS, the cards that can't be shuffled equally wont be used
        $gameState = new stdClass;

        $gameState->pile = [
            'count' => 0,
            'cards' => [],
            'last_played_cards_count' => 0,
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

        $gameState->current_player_turn = 1;
        $gameState->last_player_turn = 0;
        $gameState->turn = 1;

        //setting the players in a random order
        $players = RoomUsers::select('user_id')->where('room_id', $roomId)->inRandomOrder()->get();
        $cardAmount = Card::count(); 
        // $cardsPerPlayer = floor($cardAmount/count($players)); // USE THIS WHEN WE STOP TESTING
        
        foreach ($players as $player) {
            array_push($gameState->players, $player->user_id);
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

    private function updateGameState($gameId, $gameState, $broadcast = true) {
        $game = Game::find($gameId);
        if ($game) {
            $game->game_state = $gameState;
            $game->save();
            if ($broadcast) {
                broadcast(new UpdateGameState($game));
            }
            Log::info('Updated game state successfully!');
            return true;
        } else {
            Log::error('Something went wrong while updating state!');
            return false;
        }
    }

    private function changeTurnTo($gameId, $playerNum, $lie = true) {
        $game = Game::find($gameId);
        $gameState = $this->getDecodedGameStateById($gameId);
        $lastPlayerTurn = $gameState->last_player_turn;
        Log::info("last player was".$lastPlayerTurn);
        $lastPlayer = 'player'.$lastPlayerTurn;

        if ($lastPlayerTurn != 0 && $gameState->player_decks->{$lastPlayer}->count <= 0 && $lie) { //we put $lastPlayerTurn between brackets so it actually does what we want and doesen't search for "count" inside $lastPlayerTurn
            $this->gameWon($gameId);
            return true;
        }
        $gameState->last_player_turn = $gameState->current_player_turn;
        $gameState->current_player_turn = $playerNum;
        $gameState->turn = $gameState->turn + 1;

        $jsonGameState = json_encode($gameState);
        $this->updateGameState($gameId, $jsonGameState, false);
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

    private function takeCards($gameId, $playerNum) {
        $gameState = $this->getDecodedGameStateById($gameId);


        // $positionPlayerTaker = array_search($idPlayerTaker, $players); //rep la posicio del jugador amb id idPlayerTaker de la array players
        $playerGrabbed = 'player'.$playerNum;
        $playerTakerDeck = $gameState->player_decks->{$playerGrabbed};

        $playerTakerCards = $playerTakerDeck->cards;

        $cardsInPile = $gameState->pile->cards;

        $newPlayerTakerCards = array_merge($cardsInPile, $playerTakerCards);

        $gameState->player_decks->{$playerGrabbed}->count = count($newPlayerTakerCards);
        $gameState->player_decks->{$playerGrabbed}->cards = $newPlayerTakerCards;

        $gameState->pile = [ //reset de la pila
            'count' => 0,
            'cards' => [],
            'last_played_cards_count' => 0,
            'last_played_cards' => [],
            'called_rank' => '0',
        ];

        $jsonGameState = json_encode($gameState);

        $result = $this->updateGameState($gameId, $jsonGameState);
        return $result;
    }

    private function gameWon($gameId){
        $game = Game::find($gameId);
        $game->is_finished = '1';
        broadcast(new GameWon($game));
        $game->save();

        $room = $game->room;
        $room->state = 'lobby';
        $room->private = '1';
        $room->save();

        //Save the win and give points accordingly
        $gameState = $this->getDecodedGameStateById($gameId);

        $playerAmount = count($gameState->players);
        $podium = [];

        for ($i=1; $i <= $playerAmount; $i++) { 
            $playerResult = new stdClass;
            $playerResult->count = $gameState->player_decks->{'player'.$i}->count;
            $playerResult->playerId = $gameState->players[$i-1];
            array_push($podium, $playerResult);
        }
        //turn into a collection to sort it in a cleaner way
        $podium = collect($podium);
        $podium = $podium->sortBy('count');
        $podium = $podium->values()->all();

        //Save the positions and amount of points gained in the database
        $position = 1;
        $points = [50, 40, 30, 20, 10, 5];
        foreach ($podium as $playerResult) { 
            $winner = $position === 1;
            $pointsGained = $points[$position-1];
            $leaderboardId = $this->updateLeaderboard($playerResult->playerId, $pointsGained, $winner);
            $game->leaderboards()->syncWithoutDetaching([
                $leaderboardId => [
                    'points_gained' => $pointsGained,
                    'position' => $position++,
                ]
            ]);
        }

    }

    private function updateLeaderboard($userId, $pointsGained, $wonGame) {
        $leaderboard = Leaderboard::where('user_id', $userId)->first();
        if (!$leaderboard) {
            $leaderboard = new Leaderboard();
            $leaderboard->user_id = $userId;
            $leaderboard->wins = 0;
            $leaderboard->losses = 0;
            $leaderboard->matches = 0;
            $leaderboard->points = 0;
        }
        $leaderboard->matches += 1;
        if ($wonGame) $leaderboard->wins += 1;
        $leaderboard->points += $pointsGained;
        $leaderboard->save();
        return $leaderboard->id;
    }
}
