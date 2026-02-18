import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import * as yup from 'yup'
import { useValidation } from './useValidation'
import { useToast } from './useToast'

export default function useLeaderboards() {
    const { errors, validate, clearErrors, hasError, getError } = useValidation()
    const router = useRouter()
    const toast = useToast()

    const isLoading = ref(false)

    const leaderboards = ref([]);

    const initialLeaderboard = {
        user_id: '',
        wins: '',
        losses: '',
        matches: '',
        points: '',
    }
    const leaderboard = ref({...initialLeaderboard})

    const validationErrors = errors

    const leaderboardSchema = yup.object({
            user_id: yup.integer("The field user_id must be whole number").required("A leaderboard must be linked to a user."),
            wins: yup.integer("The field wins must be whole number").required("The field wins can't be empty").min(0, "The field wins can't be negative"),
            losses: yup.integer("The field losses must be whole number").required("The field losses can't be empty").min(0, "The field losses can't be negative"),
            matches: yup.integer("The field matches must be whole number").required("The field matches can't be empty").min(0, "The field matches can't be negative"),
            points: yup.integer("The field points must be whole number").nullable().min(0, "The field points can't be negative"),
        })

    const getLeaderboards = async () => {
        return axios.get('/api/leaderboards')
            .then(response => {
                leaderboards.value = response.data;
                return response;
            })
    }

    const getLeaderboard = async (id) => {
        return axios.get('/api/leaderboards/' + id)
            .then(response => {
                leaderboard.value = response.data.data;
                return response;
            })
    }

    const storeLeaderboard = async (leaderboard) => {
        if (isLoading.value) return;

        isLoading.value = true
        clearErrors()

        const { isValid } = validate(leaderboardSchema, leaderboard)
        if (!isValid) {
            isLoading.value = false
            return
        }

        const serializedLeaderboard = serializeLeaderboard(leaderboard)

        axios.leaderboard('/api/leaderboards', serializedLeaderboard, {
            headers: {
                "content-type": "multipart/form-data"
            }
        })
            .then(response => {
                //router.push({ name: 'leaderboards.index' })
                toast.crud.created('Leaderboard')
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const resetLeaderboard = () => {
        leaderboard.value = { ...initialLeaderboard }
        clearErrors()
    }

    const updateLeaderboard = async (leaderboard) => {
        if (isLoading.value) return;

        isLoading.value = true
        clearErrors()

        const { isValid } = validate(leaderboardSchema, leaderboard)
        if (!isValid) {
            isLoading.value = false
            return
        }

        axios.put('/api/leaderboards/' + leaderboard.id, leaderboard)
            .then(response => {
                router.push({ name: 'leaderboards.index' })
                toast.crud.updated('Leaderboard')
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteLeaderboard = async (id) => {
        axios.delete('/api/leaderboards/' + id)
            .then(response => {
                getLeaderboards()
                toast.crud.deleted('Leaderboard')
            })
            .catch(error => {
                toast.error('Error', 'Can\'t delete leaderboard')
            })
    }

    const serializeLeaderboard = (data) => {
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
        leaderboards,
        leaderboard,
        getLeaderboards,
        getLeaderboard,
        storeLeaderboard,
        updateLeaderboard,
        deleteLeaderboard,
        resetLeaderboard,
        hasError,
        getError,
        validationErrors,
        isLoading
    }
}
