<template>
    <div class="flex h-full max-h-[70vh] min-h-0 w-full flex-col overflow-hidden md:max-h-none">
        <!-- Messages -->
        <div
            class="order-2 mt-3 flex-1 min-h-0 overflow-hidden rounded-3xl bg-purple-300/20 p-3 md:order-1 md:mt-0 md:mb-3 md:p-4"
        >
            <div
                ref="messagesContainer"
                class="h-full overflow-y-auto px-4 py-3"
            >
                <div v-if="messages.length > 0" class="flex flex-col">
                    <div
                        v-for="message in messages"
                        :key="message.id ?? `${message.user ?? 'system'}-${message.text}`"
                        class="border-b border-b-white/20 py-3 text-white last:border-b-0"
                    >
                        <p v-if="message.user" class="text-sm font-semibold text-white/90">
                            {{ message.user || "Unknown" }}:
                        </p>

                        <p class="mt-1 break-words text-sm text-white md:text-base">
                            {{ message.text }}
                        </p>
                    </div>
                </div>

                <div
                    v-else
                    class="flex h-full items-center justify-center text-center text-sm text-white/75 md:text-base"
                >
                    No messages yet.
                </div>
            </div>
        </div>

        <!-- Input -->
        <div
            class="order-1 shrink-0 rounded-3xl bg-purple-300/20 p-3 md:order-2 md:p-4"
        >
            <div class="flex flex-col gap-3 sm:flex-row">
                <InputText
                    v-model="currentMessage"
                    placeholder="Write message..."
                    class="chat-input w-full"
                    :maxlength="maxLength"
                    @keyup.enter="send"
                />

                <Button
                    label="Send"
                    severity="secondary"
                    :disabled="chatLoading || !currentMessage.trim()"
                    class="chat-send-btn rounded-2xl! px-6! font-semibold!"
                    @click="send"
                />
            </div>

            <small class="mt-2 flex justify-start pl-2 text-white/70">
                {{ currentMessage.length }}/{{ maxLength }}
            </small>
        </div>
    </div>
</template>

<script setup lang="ts">
import { nextTick, onMounted, onUnmounted, ref, watch } from "vue";
import useMessages from "../../composables/messages";
const props = defineProps<{
    roomId: number;
}>();


const currentMessage = ref("");
const messages = ref<any[]>([]);
const maxLength = 300;
const messagesContainer = ref<HTMLElement | null>(null);
const { sendMessage, chatLoading } = useMessages();
const scrollToBottom = async () => {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

watch(
    messages,
    async () => {
        await scrollToBottom();
    },
    { deep: true }
);

onMounted(async () => {
    window.Echo.join(`chat.room.${props.roomId}`)
        .listen("MessageSent", (e: any) => {
            messages.value.push({
                id: e.message.id ?? Date.now(),
                user: e.message.user_name,
                text: e.message.text
            });
        })
        .joining((user: any) => {
            messages.value.push({
                id: `join-${user.id}-${Date.now()}`,
                text: `${user.name} joined the room.`
            });
        })
        .leaving((user: any) => {
            messages.value.push({
                id: `leave-${user.id}-${Date.now()}`,
                text: `${user.name} left the room.`
            });
        });

    await scrollToBottom();
});

onUnmounted(() => {
    window.Echo.leave(`chat.room.${props.roomId}`);
});

const send = async () => {
    sendMessage(currentMessage.value, props.roomId)
    currentMessage.value = ""
};
</script>

<style scoped>
.chat-input {
    background: rgba(255, 255, 255, 0.12) !important;
    border: 1px solid rgba(255, 255, 255, 0.28) !important;
    color: white !important;
    border-radius: 1rem !important;
    padding: 0.9rem 1rem !important;
}

.chat-input::placeholder {
    color: rgba(255, 255, 255, 0.65) !important;
}

:deep(.chat-input .p-inputtext),
:deep(.p-inputtext.chat-input) {
    background: rgba(255, 255, 255, 0.12) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.28) !important;
    box-shadow: none !important;
}

:deep(.p-inputtext.chat-input::placeholder) {
    color: rgba(255, 255, 255, 0.65) !important;
}

:deep(.p-inputtext.chat-input:enabled:focus) {
    border-color: rgba(255, 255, 255, 0.45) !important;
    box-shadow: none !important;
}

.chat-send-btn {
    min-height: 52px;
    box-shadow: none !important;
}
</style>    