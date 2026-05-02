<template>
    <Dialog
        v-model:visible="visible"
        modal
        :closable="false"
        class="join-room-dialog"
        :style="{ width: '25rem' }"
    >
        <template #header>
            <div class="w-full text-center">
                <h2 class="text-2xl font-bold text-white">
                    Join Room
                </h2>
                <p class="mt-1 text-sm text-white/80">
                    Enter a room code to join an existing game
                </p>
            </div>
        </template>

        <template #default>
            <div class="text-white">
                <div class="rounded-3xl bg-purple-300/35 p-5 shadow-[0_15px_20px_rgba(0,0,0,0.28)]">
                    <FloatLabel variant="over">
                        <InputText
                            id="code"
                            v-model="code"
                            class="join-room-input w-full"
                            autocomplete="off"
                            @keyup.enter="joinRoomWithCode"
                        />
                        <label for="code" class="">Room Code</label>
                    </FloatLabel>

                    <p class="mt-3 text-sm text-white/70">
                        Example: 67EB3645
                    </p>
                </div>

                <div class="mt-6 flex justify-center gap-3">
                    <Button
                        label="Enter"
                        severity="success"
                        class="rounded-2xl! px-6! py-3! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.3)]"
                        @click="joinRoomWithCode"
                    />
                    <Button
                        label="Cancel"
                        severity="danger"
                        class="rounded-2xl! px-6! py-3! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.3)]"
                        @click="visible = false"
                    />
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { ref } from 'vue';
import useRooms from "@/composables/rooms.js";
import { useRouter } from 'vue-router';

const { joinRoomByCode } = useRooms();
const code = ref('');
const router = useRouter();
const visible = defineModel('visible');

const joinRoomWithCode = async () => {
    if (!code.value?.trim()) return;

    joinRoomByCode(code.value.trim())
        .then((data) => {
            visible.value = false;
            code.value = '';
            router.push({ name: 'lobby', params: { id: data.id } });
        })
        .catch((error) => {
            console.log(error);
        });
};
</script>

<style>
.join-room-dialog.p-dialog {
    background: #520b93 !important;
    color: white !important;
    border: none !important;
    border-radius: 1.5rem !important;
    overflow: hidden !important;
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.35) !important;
}

.join-room-dialog .p-dialog-header {
    background: #520b93 !important;
    color: white !important;
    border: none !important;
    padding: 1.25rem 1.25rem 0.75rem 1.25rem !important;
}

.join-room-dialog .p-dialog-title {
    color: white !important;
}

.join-room-dialog .p-dialog-content {
    background: #520b93 !important;
    color: white !important;
    padding: 1rem 1.25rem 1.25rem 1.25rem !important;
}

.join-room-dialog .p-dialog-footer {
    background: #520b93 !important;
    color: white !important;
    border: none !important;
}

.p-dialog-mask {
    backdrop-filter: blur(4px);
}

.join-room-dialog .p-floatlabel,
.join-room-dialog .p-float-label {
    width: 100%;
}

.join-room-dialog .p-floatlabel label,
.join-room-dialog .p-float-label label,
.join-room-dialog label[for="code"] {
    color: rgba(255, 255, 255, 0.85) !important;
}

.join-room-input,
.join-room-input.p-inputtext,
input.join-room-input,
.p-inputtext.join-room-input {
    width: 100% !important;
    background: rgba(255, 255, 255, 0.12) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.24) !important;
    border-radius: 1rem !important;
    box-shadow: none !important;
    padding: 0.9rem 1rem !important;
}

.join-room-input::placeholder,
.p-inputtext.join-room-input::placeholder {
    color: rgba(255, 255, 255, 0.55) !important;
}

.join-room-input:enabled:focus,
.join-room-input.p-inputtext:enabled:focus,
.p-inputtext.join-room-input:enabled:focus {
    background: rgba(255, 255, 255, 0.14) !important;
    color: white !important;
    border-color: rgba(255, 255, 255, 0.849) !important;
    box-shadow: 0 0 0 0.15rem rgba(255, 255, 255, 0.08) !important;
}

.join-room-dialog .p-button {
    color: white !important;
}

</style>