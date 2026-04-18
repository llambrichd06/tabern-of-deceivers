import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import * as yup from 'yup'
import { useValidation } from './useValidation'
import { useToast } from './useToast'

export default function useGames() {
    const { errors, validate, clearErrors, hasError, getError } = useValidation()
    const router = useRouter()
    const toast = useToast()

    const isLoading = ref(false)

    const games = ref([]);

    const initialGame = {
        room_id: '',
        is_finished: '',
        game_state: {
                      "pile": {
                        "count": 0,
                        "last_played_cards_count": 0,
                        "cards": [],
                        "last_played_cards": [],
                        "called_rank": "0"
                      },
                      "player_decks": {
                        "player1": {
                          "count": 0,
                          "cards": []
                        },
                        "player2": {
                          "count": 0,
                          "cards": []
                        },
                        "player3": {
                          "count": 0,
                          "cards": []
                        },
                        "player4": {
                          "count": 0,
                          "cards": []
                        },
                        "player5": {
                          "count": 0,
                          "cards": []
                        },
                        "player6": {
                          "count": 0,
                          "cards": []
                        }
                      },
                      "players": [],
                      "current_player_turn": 0,
                      "last_player_turn": 0,
                      "turn": 0
                    },
    }
    const game = ref({...initialGame})

    const validationErrors = errors

    const gameSchema = yup.object({

    })

    const startGame = (room_id) => {
        axios.post('/api/games/startGame', {room_id: room_id}) //Was failing cause you gotta send the data as an object
            .then(response => {
                console.log(response.data)
            }).catch(error =>{
                toast.crud.errorMsgFromError(error);
            })
    }
    const getGame = async (id) => {
        return axios.get('/api/games/'+id)
            .then(response => {
                game.value = response.data.game
                return response
            }).catch(error =>{
                toast.crud.errorMsgFromError(error);
            }).finally(isLoading.value = false)
    }
    
    /**
     * GAMEPLAY FUNCTIONS
     */
    const playCards = (gameId, cardIds, calledRank) => {
        if (isLoading.value) {
            toast.error('Currently loading! Wait a bit and try again.')
            return
        }
        isLoading.value = true; 
        
        axios.post('/api/games/playCards', {gameId:gameId, idCards: cardIds, calledRank: calledRank})
            .then(response => {
                // console.log(response.data)
            }).catch(error =>{
                toast.crud.errorMsgFromError(error);
            })
    }
    return {
        games,
        game,
        startGame,
        getGame,
        playCards,
        hasError,
        getError,
        validationErrors,
        isLoading,
        errors
    }
}