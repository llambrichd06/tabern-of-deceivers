<template>
    <div class="w-full">
        <!-- Messages -->
        <div
			v-if="messages.length > 0"
			class="mb-4 max-h-[320px] overflow-y-auto rounded-3xl bg-purple-300/20 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.2)]"
		>
			<div class="bg-white/10 rounded-2xl py-5 px-4">
				<div
					v-for="message in messages"
					:key="message.id"
					class=" py-3 text-white border-b border-b-white/20"
				>
                    <p v-if="message.user" class="text-sm font-semibold text-white/90">
						{{ message?.user || "Unknown" }}:
					</p>
					<p class="mt-1 text-sm md:text-base text-white">
						{{ message.text }}
					</p>
				</div>
			</div>
		</div>

        <!-- Input -->
        <div class="rounded-3xl bg-purple-300/20 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
            <div class="flex flex-col gap-3 sm:flex-row">
                <InputText
                    v-model="currentMessage"
                    placeholder="Write message..."
                    class="chat-input w-full"
                    @keyup.enter="sendMessage"
                />

                <Button
                    label="Send"
                    severity="secondary"
                    :disabled="chatLoading"
                    class="chat-send-btn rounded-2xl! px-6! font-semibold! shadow-[0_10px_16px_rgba(0,0,0,0.25)]"
                    @click="sendMessage"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";

const props = defineProps<{
	roomId: integer;
}>()
const chatLoading = ref(false);
const currentMessage = ref('')
const messages = ref([])

onMounted(async () => {
	window.Echo.join(`chat.room.${props.roomId}`)
	    .listen('MessageSent', (e) => {
        	// Standard event listener for messages within that room
        	console.log(e);
        	// messages.value.push(e.message.user_name + ': ' + e.message.text )
            messages.value.push({
                user: e.message.user_name,
                text: e.message.text
            });
        })
        .joining((user) => {
            // Runs when a new person joins
        	messages.value.push({text:`${user.name} joined the room.`})
        })
        .leaving((user) => {
            // Runs when someone closes the tab or disconnects
        	messages.value.push({text:`${user.name} left the room.`})
        })
})

const sendMessage = () => {
    if (!chatLoading.value && currentMessage.value) {
        chatLoading.value = true;
        
        axios.post('/api/messages/sent/'+props.roomId,{text: currentMessage.value})
        .then(response => {
            console.log(response);
        }).catch(error =>{
            console.log(error);
        }).finally(
            chatLoading.value = false,
            currentMessage.value = ""
        )
    }
}
</script>

<style scoped>
.chat-input {
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.22) !important;
    color: white !important;
    border-radius: 1rem !important;
    padding: 0.9rem 1rem !important;
}

.chat-input::placeholder {
    color: rgba(255, 255, 255, 0.65) !important;
}

/* PrimeVue overrides */
:deep(.chat-input .p-inputtext),
:deep(.p-inputtext.chat-input) {
    background: rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.22) !important;
    box-shadow: none !important;
}

:deep(.p-inputtext.chat-input::placeholder) {
    color: rgba(255, 255, 255, 0.65) !important;
}

:deep(.p-inputtext.chat-input:enabled:focus) {
    border-color: rgba(255, 255, 255, 0.4) !important;
    box-shadow: 0 0 0 0.15rem rgba(255, 255, 255, 0.08) !important;
}

.chat-send-btn {
    min-height: 52px;
}
</style>