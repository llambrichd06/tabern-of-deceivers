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
            room_code: yup.string().trim(),
            state: yup.string().trim().oneOf(["lobby","on_going","completed"]).required('The state of the game is required'),
            host_id: yup.number().integer().required("You need a host to have a game"),
            private: yup.boolean().nullable(),
        })
    const roomCodeSchema = yup.string().required('Code is required').length(6, 'Must be 6 characters')

    const getRooms = async () => {
        return axios.get('/api/rooms')
            .then(response => {
                rooms.value = response.data.data || response.data;
                return response;
            })
    }

    const getRoom = async (id) => {
        return axios.get('/api/rooms/' + id)
            .then(response => {
                let responseDatas = response.data.data || response.data
                responseDatas.private = responseDatas.private == 0 ? false : true;
                room.value = response.data.data;
                return response;
            })
    }

    const storeRoom = async (room) => {
        if (isLoading.value) return;

        isLoading.value = true
        clearErrors()
        
        room.private = room.private ? 1 : 0;

        const { isValid } = validate(roomSchema, room)
        if (!isValid) {
            isLoading.value = false

            return
        }

        const serializedRoom = serializeRoom(room)

        return axios.post('/api/rooms', serializedRoom, {
            headers: {
                "content-type": "multipart/form-data"
            }
        })
            .then(response => {
                toast.crud.created('Room')
                return response.data.id;
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
        
        room.private = room.private ? 1 : 0;

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
                toast.crud.error('Delete room. Message:'+error.response?.data)
            })
    }

    const getOpenRooms = () => {
        axios.get('/api/rooms/openRooms')
            .then(response => {
                rooms.value = response.data.public_rooms
                return response;
            })
    }


    const joinRoomByCode = async (roomCode) => {
        if (isLoading.value) return;
        isLoading.value = true;

        const { isValid } = validate(roomCodeSchema, roomCode);
        // if (!isValid) {
        //     isLoading.value = false
        //     return
        // }
        return await axios.get('/api/rooms/joinRoomWithCode', { //WE HAVE TO RETURN THE CALL ITSELF TO RETURN A PROMISE
            //if we dont return the call, whatever is calling this wont get a promise and will immediatelly execute even with an await
            params: { //A cleaner way to do get parameters
                room_code: roomCode
            }
        })
        .then(response => {
            return response.data
        }).catch(error =>{
            // let errorMessage;
            // if (error.name === 'ValidationError') {
            //     errorMessage = error.message;
            // } else {
            //     errorMessage = error.response.data.error;
            // }
            // toast.crud.error('Join room. Message:'+errorMessage);
            toast.crud.errorMsgFromError(error);
        }).finally(isLoading.value = false)
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

    const changePrivate = async (room_id) => {
        if (isLoading.value) return;

        isLoading.value = true;

        try {
            await axios.get('/api/rooms/changePrivate', {
            params: {
                room_id,
            },
            });
        } catch (error) {
            toast.crud.errorMsgFromError(error)
        } finally {
            isLoading.value = false;
        }
    };

    const transferOwnership = async (room_id, player_id) => {
        if (isLoading.value) return;
        isLoading.value = true;
        try {
            await axios.get('/api/rooms/transferOwnership', {
            params: {
                room_id,
                player_id,
            },
            });
        } catch (error) {
            toast.crud.errorMsgFromError(error)
        } finally {
            isLoading.value = false;
        }
    };
    const leaveRoom = async (room_id) => {
        if (isLoading.value) return;

        isLoading.value = true;

        try {
            await axios.get('/api/rooms/leaveRoom', {
            params: {
                room_id,
            },
            });

            await router.push({ name: 'rooms' });
        } catch (error) {
            toast.crud.errorMsgFromError(error)
        } finally {
            isLoading.value = false;
        }
    };

    return {
        rooms,
        room,
        getRooms,
        getRoom,
        getOpenRooms,
        storeRoom,
        updateRoom,
        deleteRoom,
        resetRoom,
        joinRoomByCode,
        changePrivate,
        transferOwnership,
        leaveRoom,
        hasError,
        getError,
        validationErrors,
        isLoading,
        errors
    }
}
