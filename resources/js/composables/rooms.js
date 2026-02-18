import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import * as yup from 'yup'
import { useValidation } from './useValidation'
import { useToast } from './useToast'

export default function useRooms() {
    const { errors, validate, clearErrors, hasError, getError } = useValidation()
    const router = useRouter()
    const toast = useToast()

    const isLoading = ref(false)

    const rooms = ref([]);

    const initialRoom = {
        room_code: '',
        state: '',
        host_id: '',
        private: ''
    }
    const room = ref({...initialRoom})

    const validationErrors = errors

    const roomSchema = yup.object({
            room_code: yup.string().trim().required('Error, Room_code has not been put correcly, try again'),
            state: yup.string().trim().oneOf(["lobby","on_going","completed"]).required('The state of the game is required'),
            host_id: yup.integer().required("You need a host to have a game"),
            private: yup.boolean().nullable(),
        })

    const getRooms = async () => {
        return axios.get('/api/rooms')
            .then(response => {
                rooms.value = response.data;
                return response;
            })
    }

    const getRoom = async (id) => {
        return axios.get('/api/rooms/' + id)
            .then(response => {
                room.value = response.data.data;
                return response;
            })
    }

    const storeRoom = async (room) => {
        if (isLoading.value) return;

        isLoading.value = true
        clearErrors()

        const { isValid } = validate(roomSchema, room)
        if (!isValid) {
            isLoading.value = false
            return
        }

        const serializedRoom = serializeRoom(room)

        axios.room('/api/rooms', serializedRoom, {
            headers: {
                "content-type": "multipart/form-data"
            }
        })
            .then(response => {
                //router.push({ name: 'rooms.index' })
                toast.crud.created('Room')
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const resetRoom = () => {
        room.value = { ...initialRoom }
        clearErrors()
    }

    const updateRoom = async (room) => {
        if (isLoading.value) return;

        isLoading.value = true
        clearErrors()

        const { isValid } = validate(roomSchema, room)
        if (!isValid) {
            isLoading.value = false
            return
        }

        axios.put('/api/rooms/' + room.id, room)
            .then(response => {
                router.push({ name: 'rooms.index' })
                toast.crud.updated('Room')
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteRoom = async (id) => {
        axios.delete('/api/rooms/' + id)
            .then(response => {
                getRooms()
                toast.crud.deleted('Room')
            })
            .catch(error => {
                toast.error('Error', 'Can\'t delate room')
            })
    }

    const serializeRoom = (data) => {
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
        rooms,
        room,
        getRooms,
        getRoom,
        storeRoom,
        updateRoom,
        deleteRoom,
        resetRoom,
        hasError,
        getError,
        validationErrors,
        isLoading
    }
}
