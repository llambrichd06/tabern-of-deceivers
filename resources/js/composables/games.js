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
        game_state: '',
    }
    const game = ref({...initialGame})

    const lieResult = ref('')

    const validationErrors = errors

    const gameSchema = yup.object({
            room_id: yup.number().required(),
            is_finished: yup.boolean().required(),
            game_state: yup.string().required(),
    })

    const getGames = async () => {
        return axios.get('/api/games')
            .then(response => {
                games.value = response.data.game || response.game;
                return response;
            })
    }

    const getGame = async (id) => {
        return axios.get('/api/games/' + id)
            .then(response => {
                let responseDatas = response.data.game || response.game
                responseDatas.is_finished = responseDatas.is_finished == 0 ? false : true;
                game.value = response.data.game;
                return response;
            })
    }

    const storeGame = async (game) => {
        if (isLoading.value) return;

        isLoading.value = true
        clearErrors()
        
        game.is_finished = game.is_finished ? 1 : 0;

        const { isValid } = validate(gameSchema, game)
        if (!isValid) {
            isLoading.value = false

            return
        }

        const serializedGame = serializeGame(game)

        return axios.post('/api/games', serializedGame, {
            headers: {
                "content-type": "multipart/form-data"
            }
        })
            .then(response => {
                toast.crud.created('Game')
                return response.data.game;
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const resetGame = () => {
        game.value = { ...initialGame }
        clearErrors()
    }

    const updateGame = async (game) => {
        if (isLoading.value) return;
        
        isLoading.value = true
        clearErrors()
        
        game.is_finished = game.is_finished ? 1 : 0;

        const { isValid } = validate(gameSchema, game)
        if (!isValid) {
            isLoading.value = false
            return
        }

        axios.put('/api/games/' + game.id, game)
            .then(response => {
                router.push({ name: 'games.index' })
                toast.crud.updated('Game')
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteGame = async (id) => {
        axios.delete('/api/games/' + id)
            .then(response => {
                getGames()
                toast.crud.deleted('Game')
            })
            .catch(error => {
                toast.crud.error('Delete game. Message:'+error.response?.data)
            })
    }

    const startGame = (room_id) => {
        axios.post('/api/games/startGame/'+room_id)
            .then(response => {
                console.log(response.data)
            }).catch(error =>{
                toast.crud.errorMsgFromError(error);
            })
    }
    const getGameState = async (id) => {
        return axios.get('/api/games/gameState/'+id)
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
                console.log(response.data)
            }).catch(error =>{
                toast.crud.errorMsgFromError(error);
            })
    }
    const callLie = (gameId) => {
        if (isLoading.value) {
            toast.error('Currently loading! Wait a bit and try again.')
            return
        }
        isLoading.value = true; 
        
        axios.post('/api/games/callLie/'+gameId)
            .then(response => {
                console.log(response.data)
            }).catch(error =>{
                toast.crud.errorMsgFromError(error);
            })
    }
    const serializeGame = (data) => {
        const form = new FormData()
        Object.entries(data).forEach(([key, value]) => {
            if (value === undefined || value === null) return
            if (Array.isArray(value)) {
                value.forEach(item => form.append(`${key}[]`, item))
            } else {
                form.append(key, value)
            }
        })
        return form
    }
    return {
        games,
        game,
        getGames,
        getGame,
        storeGame,
        updateGame,
        deleteGame,
        startGame,
        getGameState,
        playCards,
        callLie,
        hasError,
        getError,
        validationErrors,
        isLoading,
        errors
    }
}