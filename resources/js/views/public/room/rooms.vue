<template>
    <div class="min-h-screen bg-[#520B93] text-white">
        <!-- ------------------------------ Rooms ------------------------------ -->
        <section class="px-4 pt-2 pb-10 md:pt-1 md:pb-14">
            <div class="mx-auto w-full max-w-7xl rounded-3xl bg-[#520B93]">
                <!-- Header -->
                <div class="mb-10 text-center">
                    <h1 class="mb-6 text-4xl font-extrabold tracking-tight md:text-6xl">
                        Available Rooms
                    </h1>

                    <p class="mx-auto max-w-3xl text-base leading-7 text-white/90 md:text-lg">
                        Join an open room, host your own game, or jump into a quick match.
                    </p>
                </div>

                <!-- ------------------------------ Menu Buttons ------------------------------ -->
                <section class="px-4 pt-0 pb-10">
                    <div class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-3 sm:grid-cols-3">
                        <Button
                            label="Host Game"
                            severity="secondary"
                            class="h-16 rounded-2xl! text-lg! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.28)]"
                            @click="hostGame"
                        />
                        <Button
                            label="Search Game"
                            severity="secondary"
                            class="h-16 rounded-2xl! text-lg! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.28)]"
                            @click="openJoinGame"
                        />
                        <Button
                            label="Refresh Rooms"
                            severity="secondary"
                            class="h-16 rounded-2xl! text-lg! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.28)]"
                            @click="refreshRooms"
                        />
                    </div>
                </section>

                <!-- Rooms list -->
                <div class="rounded-b-3xl bg-[#520B93] px-2 pb-4 pt-2 md:px-0">
                    <DataView :value="rooms" paginator :rows="6" layout="grid">
                        <template #grid="slotProps">
                            <div class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 xl:grid-cols-3">
                                <div
                                    v-for="item in slotProps.items"
                                    :key="item.id"
                                >
                                    <div class="h-full rounded-3xl bg-purple-300/35 p-5 text-white shadow-[0_15px_20px_rgba(0,0,0,0.28)]">
                                        <div class="flex min-h-[300px] flex-col">
                                            <!-- Top -->
                                            <div class="flex items-start justify-between gap-4 border-b border-white/20 pb-4">
                                                <div>
                                                    <p class="text-sm font-medium uppercase tracking-wide text-white/70">
                                                        Room
                                                    </p>
                                                    <p class="text-lg font-semibold text-white">
                                                        {{ item.room_code }}
                                                    </p>
                                                </div>

                                                <div class="text-right">
                                                    <p class="text-sm font-medium uppercase tracking-wide text-white/70">
                                                        Host
                                                    </p>
                                                    <p class="text-lg font-semibold text-white">
                                                        {{ item.host?.name || "Unknown" }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Center -->
                                            <div class="flex flex-1 flex-col justify-center py-5">
                                                <div class="mb-4 text-center">
                                                    <p class="text-base font-semibold text-white">
                                                        Players {{ item.players.length }}/6
                                                    </p>
                                                </div>

                                                <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                                                    <div
                                                        v-for="(player, index) in playerSlots(item.players)"
                                                        :key="index"
                                                        class="flex items-center gap-2 rounded-2xl border border-white/10 bg-white/10 px-3 py-2"
                                                    >
                                                        <div
                                                            class="flex h-7 w-7 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-white/40 bg-white/10"
                                                            :class="{ 'border-dashed bg-transparent': !player }"
                                                        >
                                                            <img
                                                                v-if="player"
                                                                :src="getUserAvatar(player)"
                                                                :alt="player.name ?? 'Player avatar'"
                                                                class="h-full w-full object-cover"
                                                                @error="handleAvatarError"
                                                            />

                                                            <span v-else class="text-xs text-white/50">
                                                                +
                                                            </span>
                                                        </div>

                                                        <div class="min-w-0 flex-1">
                                                            <p class="truncate text-sm font-medium text-white">
                                                                {{ player?.name || "Empty slot" }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bottom -->
                                            <div class="border-t border-white/20 pt-4">
                                                <div class="flex justify-center">
                                                    <Button
                                                        label="Join Room"
                                                        severity="secondary"
                                                        class="w-full rounded-2xl! px-8! py-3! text-base! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.3)]"
                                                        @click="joinPublic(item.id)"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template #empty>
                            <div class="py-12 text-center text-white">
                                <p class="text-xl font-semibold">No rooms available</p>
                                <p class="mt-2 text-white/80">
                                    Try hosting a game or searching for one.
                                </p>
                            </div>
                        </template>
                    </DataView>
                </div>
            </div>
        </section>

        <WaitingQuickPlay v-model:visible="showQuickPlayDialog" />
        <JoinRoom v-model:visible="joinRoomVisible" />
    </div>
</template>

<script setup>
import { authStore } from "@/store/auth";
import { ref, onMounted } from "vue";
import useRooms from "@/composables/rooms.js";
import WaitingQuickPlay from "../../../components/roomComponents/WaitingQuickPlay.vue";
import JoinRoom from "../../../components/roomComponents/JoinRoom.vue";
import { useRouter } from "vue-router";

const { getOpenRooms, joinPublicRoom, rooms, storeRoom, room, hostRoom } = useRooms();

const showQuickPlayDialog = ref(false);
const joinRoomVisible = ref(false);
const auth = authStore();
const router = useRouter();

const fallbackAvatar = "/images/placeholder.png";

onMounted(async () => {
    await getOpenRooms();
});

const hostGame = async () => {
    const data = await hostRoom(room.value);
    console.log(data);

    router.push({ name: "lobby", params: { id: data.id } });
};

const refreshRooms = () => {
    getOpenRooms();
};

const openJoinGame = () => {
    joinRoomVisible.value = true;
};

const joinPublic = async (id) => {
    const data = await joinPublicRoom(id);
    router.push({ name: "lobby", params: { id: data.id } });
};

const playerSlots = (players = []) => {
    return Array.from({ length: 6 }, (_, index) => players[index] || null);
};

const getUserAvatar = (user) => {
    return user?.avatar || fallbackAvatar;
};

const handleAvatarError = (event) => {
    event.target.src = fallbackAvatar;
};
</script>

<style scoped>
:deep(.p-dataview),
:deep(.p-dataview-content) {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

:deep(.p-paginator) {
    background: transparent !important;
    border: none !important;
    color: white !important;
    justify-content: center;
    margin-top: 1.5rem;
}

:deep(.p-paginator .p-paginator-page),
:deep(.p-paginator .p-paginator-next),
:deep(.p-paginator .p-paginator-prev),
:deep(.p-paginator .p-paginator-first),
:deep(.p-paginator .p-paginator-last) {
    color: white !important;
    background: transparent !important;
    border-radius: 0.75rem;
}

:deep(.p-paginator .p-highlight) {
    background: rgba(255, 255, 255, 0.16) !important;
    color: white !important;
}

:deep(.p-paginator .p-dropdown-label),
:deep(.p-paginator .p-dropdown-trigger),
:deep(.p-select-label),
:deep(.p-select-dropdown) {
    color: white !important;
}
</style>