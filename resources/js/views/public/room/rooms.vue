<template>
    <div class="min-h-screen bg-[#520B93] text-white">
        <!-- ------------------------------ Menu Buttons ------------------------------ -->
        <section class="px-4 pt-8">
            <div class="mx-auto grid w-full max-w-5xl grid-cols-1 gap-3 sm:grid-cols-3">
                <Button
                    label="Search Game"
                    severity="secondary"
                    class="h-16 rounded-2xl! text-lg! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.28)]"
                    @click="openJoinGame"
                />
                <Button
                    label="Host Game"
                    severity="secondary"
                    class="h-16 rounded-2xl! text-lg! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.28)]"
                    @click="hostGame"
                />
                <Button
                    label="Quick Match"
                    severity="secondary"
                    class="h-16 rounded-2xl! text-lg! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.28)]"
                    @click="searchQuickGame"
                />
            </div>
        </section>

        <!-- ------------------------------ Rooms ------------------------------ -->
        <section class="px-4 py-8">
            <div class="mx-auto w-full max-w-5xl rounded-3xl bg-[#520B93]">
                <!-- Header -->
                <div class="rounded-t-3xl bg-[#520B93] px-6 py-8 text-center md:px-8">
                    <h2 class="text-4xl font-bold text-white">
                        Available Rooms
                    </h2>
                    <p class="mx-auto mt-3 max-w-2xl text-white/85">
                        Join an open room, host your own game, or jump into a quick match.
                    </p>
                </div>

                <!-- Rooms list -->
                <div class="rounded-b-3xl bg-[#520B93] px-2 pb-4 md:px-0">
                    <DataView :value="rooms" paginator :rows="4" layout="grid">
                        <template #grid="slotProps">
                            <div class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2">
                                <div
                                    v-for="item in slotProps.items"
                                    :key="item.id"
                                >
                                    <div class="h-full rounded-3xl bg-purple-300/35 p-6 text-white shadow-[0_15px_20px_rgba(0,0,0,0.28)]">
                                        <div class="flex min-h-[260px] flex-col justify-between">
                                            <!-- Top -->
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="text-lg font-semibold text-white">
                                                    Players {{ item.players.length }}/6
                                                </div>
                                            </div>

                                            <!-- Middle -->
                                            <div class="mt-6 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                                                <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                                                    <!-- Left -->
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[0])"></div>
                                                            <div class="min-w-[90px] px-2 py-0.5 text-sm text-white">
                                                                {{ item.players[0]?.name || "" }}
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[2])"></div>
                                                            <div class="min-w-[90px] px-2 py-0.5 text-sm text-white">
                                                                {{ item.players[2]?.name || "" }}
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[4])"></div>
                                                            <div class="min-w-[90px] px-2 py-0.5 text-sm text-white">
                                                                {{ item.players[4]?.name || "" }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Right -->
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[1])"></div>
                                                            <div class="min-w-[90px] px-2 py-0.5 text-sm text-white">
                                                                {{ item.players[1]?.name || "" }}
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[3])"></div>
                                                            <div class="min-w-[90px] px-2 py-0.5 text-sm text-white">
                                                                {{ item.players[3]?.name || "" }}
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[5])"></div>
                                                            <div class="min-w-[90px] px-2 py-0.5 text-sm text-white">
                                                                {{ item.players[5]?.name || "" }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex justify-center md:justify-end">
                                                    <Button
                                                        label="Join Room"
                                                        severity="secondary"
                                                        class="rounded-2xl! px-8! py-3! text-base! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.3)]"
                                                        @click="joinPublic(item.id)"
                                                    />
                                                </div>
                                            </div>

                                            <!-- Bottom -->
                                            <div class="mt-6 border-t border-white/20 pt-4 text-right text-sm text-white/80">
                                                Host: {{ item.host?.name || "" }}
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

const { getOpenRooms, joinPublicRoom, rooms, storeRoom, room } = useRooms();

const showQuickPlayDialog = ref(false);
const joinRoomVisible = ref(false);
const auth = authStore();
const router = useRouter();

onMounted(async () => {
    await getOpenRooms();
});

const hostGame = async () => {
    room.value.host_id = auth.user.id;
    room.value.private = true;
    room.value.state = "lobby";

    const data = await storeRoom(room.value);
    router.push({ name: "lobby", params: { id: data.id } });
};

const searchQuickGame = () => {
    showQuickPlayDialog.value = true;
};

const openJoinGame = () => {
    joinRoomVisible.value = true;
};

const joinPublic = async (id) => {
    const data = await joinPublicRoom(id);
    router.push({ name: "lobby", params: { id: data.id } });
};

const circleClass = (player) => {
    return [
        "w-6 h-6 rounded-full shrink-0",
        "border-2 border-white/50",
        player ? "bg-[#520B93]" : "bg-transparent",
    ].join(" ");
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