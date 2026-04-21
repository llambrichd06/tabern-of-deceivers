<template>
    <div class="w-full">
        <!-- Messages -->
        <div
			v-if="messages.length > 0"
			class="mb-4 max-h-[320px] overflow-y-auto rounded-3xl bg-purple-300/20 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.2)]"
		>
			<div class="space-y-3">
				<div
					v-for="message in messages"
					:key="message.id"
					class="rounded-2xl bg-white/10 px-4 py-3 text-white"
				>
					<p class="text-sm font-semibold text-white/90">
						{{ message.user?.name || message.user_name || "Unknown" }}
					</p>
					<p class="mt-1 text-sm md:text-base text-white">
						{{ message.message }}
					</p>
				</div>
			</div>
		</div>

        <!-- Input -->
        <div class="rounded-3xl bg-purple-300/20 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
            <div class="flex flex-col gap-3 sm:flex-row">
                <InputText
                    v-model="newMessage"
                    placeholder="Write message..."
                    class="chat-input w-full"
                    @keyup.enter="sendMessage"
                />

                <Button
                    label="Send"
                    severity="secondary"
                    class="chat-send-btn rounded-2xl! px-6! font-semibold! shadow-[0_10px_16px_rgba(0,0,0,0.25)]"
                    @click="sendMessage"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const messages = ref([]);
const newMessage = ref("");

const sendMessage = () => {
    if (!newMessage.value.trim()) return;

    messages.value.push({
        id: Date.now(),
        user: { name: "You" },
        message: newMessage.value
    });

    newMessage.value = "";
};
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