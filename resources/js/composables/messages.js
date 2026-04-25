import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import * as yup from 'yup'
import { useValidation } from './useValidation'
import { useToast } from './useToast'

export default function useMessages() {

    const chatLoading = ref(false)

    const sendMessage = (currentMessage, roomId) => {
        const text = currentMessage.trim();

        if (!chatLoading.value && text) {
            chatLoading.value = true;

            axios.post("/api/messages/sent/" + roomId, { text })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    chatLoading.value = false;
                });
        }
    };

    return {
        chatLoading,
        sendMessage,
    }
}