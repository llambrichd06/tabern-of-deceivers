<template>
<Dialog
        v-model:visible="visible"
        modal
        :closable="false"
        class="join-room-dialog"
        :style="{ width: '20rem' }"
    >
        <template #header>
            <div class="w-full text-center">
                <h2 class="text-2xl font-bold text-white">
                    Leave ongoing game?
                </h2>
            </div>
        </template>

        <template #default>
            <div class="text-white">

                <div class=" flex justify-center gap-3">
                    <Button
                        label="Stay"
                        severity="success"
                        class="rounded-2xl! px-6! py-3! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.3)]"
                        @click="stayInMatch"
                    />
                    <Button
                        label="Quit"
                        severity="danger"
                        class="rounded-2xl! px-6! py-3! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.3)]"
                        @click="leaveMatch"
                    />
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { onBeforeRouteLeave, useRouter } from 'vue-router';
const visible = ref(false);
const router = useRouter('')
let nextStep = null; // Store the navigation callback

onBeforeRouteLeave((to, from, next) => {
    visible.value = true;
    console.log('what');
    
    nextStep = next; // store the 'next' function so we can call it
});

const leaveMatch = () => {
    visible.value = false;
    if (nextStep) {
        nextStep(); // execute the 'next' function that was saved
        nextStep = null;
    }
};

const stayInMatch = () => {
    visible.value = false;
    if (nextStep) {
        nextStep(false); // execute the next function that was saved, but we tell it to cancel the navigation
        nextStep = null;
    }
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

.join-room-dialog .p-button {
    color: white !important;
}
</style>