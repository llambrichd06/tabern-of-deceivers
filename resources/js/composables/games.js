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

    const validationErrors = errors

    const gameSchema = yup.object({

    })

    const startGame = (room_id) => {
        axios.post('/api/game/startGame', room_id)
            .then(response => {
                console.log(response.data)
            }).catch(error =>{
                toast.crud.errorMsgFromError(error);
            })
    }
    const getGame = async () => {
        return axios.get('/api/game/getGame'+id)
            .then(response => {

            })
    }

    return {
        games,
        game,
        startGame,
        getGame,
        hasError,
        getError,
        validationErrors,
        isLoading,
        errors
    }
}