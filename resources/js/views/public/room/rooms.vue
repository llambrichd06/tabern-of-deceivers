<template>
    <!-- ------------------------------ Menu Buttons ------------------------------ -->
    <section class="px-4 pt-8">
        <div class="mx-auto grid w-full max-w-5xl grid-cols-1 gap-2 sm:grid-cols-3">
            <Button label="Search Game" outlined class="h-16 rounded-none" @click="openJoinGame" />
            <Button label="Host Game" outlined class="h-16 rounded-none" @click="hostGame" />
            <Button label="Quick Match" outlined class="h-16 rounded-none" @click="searchQuickGame" />
        </div>
    </section>

    <!-- ------------------------------ Rooms ------------------------------ -->
    <section class="px-4 py-6">
        <Card class="mx-auto w-full max-w-5xl">
            <template #content>
                <DataView :value="rooms" paginator :rows="4" layout="grid">
                    <template #header>
                        <div class="border-b border-slate-300 py-4 text-center text-3xl font-medium">
                            Available rooms
                        </div>
                    </template>

                    <template #grid="slotProps">
                        <div class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2">
                            <div
                                v-for="item in slotProps.items"
                                :key="item.id"
                            >
                                <Card>
                                    <template #content>
                                        <div class="flex min-h-45 flex-col justify-between">
                                            <!-- Parte superior -->
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="text-lg font-medium">
                                                    Players {{ item.players.length }}/6
                                                </div>
                                            </div>

                                            <!-- Part del mitg i on posem els usuaris -->
                                            <div class="mt-4 flex items-center justify-between gap-6">
                                                <div class="grid grid-cols-2 gap-x-6">
                                                    <!-- part esquerra -->
                                                    <div class="space-y-2">
                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[0])"></div>
                                                            <div class="min-w-22.5 px-2 py-0.5 text-xs">
                                                                {{ item.players[0]?.name || '' }}
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[2])"></div>
                                                            <div class="min-w-22.5 px-2 py-0.5 text-xs">
                                                                {{ item.players[2]?.name || '' }}
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[4])"></div>
                                                            <div class="min-w-22.5 px-2 py-0.5 text-xs">
                                                                {{ item.players[4]?.name || '' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- part Dreta -->
                                                    <div class="space-y-2">
                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[1])"></div>
                                                            <div class="min-w-22.5 px-2 py-0.5 text-xs">
                                                                {{ item.players[1]?.name || '' }}
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[3])"></div>
                                                            <div class="min-w-22.5 px-2 py-0.5 text-xs">
                                                                {{ item.players[3]?.name || '' }}
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <div :class="circleClass(item.players[5])"></div>
                                                            <div class="min-w-22.5 px-2 py-0.5 text-xs">
                                                                {{ item.players[5]?.name || '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <Button label="Join Room" rounded outlined @click="joinPublic(item.id)"
                                                />
                                            </div>

                                            <!-- part de avaix on surt qui es el host -->
                                            <div class="mt-4 text-right text-xs text-slate-600">
                                                Host: {{ item.host?.name || '' }}
                                            </div>
                                        </div>
                                    </template>
                                </Card>
                            </div>
                        </div>
                    </template>
                </DataView>
            </template>
        </Card>
    </section>

    <WaitingQuickPlay v-model:visible="showQuickPlayDialog" />
    <JoinRoom v-model:visible="joinRoomVisible" />
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
        "h-7 w-7 rounded-full border border-slate-400",
        player ? "bg-slate-200" : "bg-transparent"
    ].join(" ");
};
</script>