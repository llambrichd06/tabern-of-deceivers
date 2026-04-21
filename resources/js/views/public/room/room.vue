<template>
    <div class="min-h-screen bg-[#520B93] text-white">
        <!-- TOP SECTION -->
        <section class="px-4 py-8">
            <div class="mx-auto max-w-5xl">
                <div class="rounded-3xl bg-purple-300/35 p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-8">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-8">
                                <p class="text-2xl font-semibold md:text-3xl">
                                    Room code: {{ room?.room_code }}
                                </p>

                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <p class="text-xl font-medium md:text-2xl">
                                            {{ room?.private === false ? 'Public' : 'Private' }}
                                        </p>
                                    </div>

                                    <div v-if="!loading && authUser.user.id === room?.host?.id">
                                        <Button
                                            v-if="room?.private === true"
                                            label="Unprivate the room"
                                            severity="secondary"
                                            class="rounded-2xl! px-5! py-2! font-semibold! shadow-[0_10px_16px_rgba(0,0,0,0.25)]"
                                            @click="privateChange"
                                        />
                                        <Button
                                            v-else
                                            label="Private the room"
                                            severity="secondary"
                                            class="rounded-2xl! px-5! py-2! font-semibold! shadow-[0_10px_16px_rgba(0,0,0,0.25)]"
                                            @click="privateChange"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="!loading && authUser.user.id === room?.host?.id" class="flex flex-wrap items-center gap-2">
                            <Button
                                label="Leave Room"
                                severity="danger"
                                class="rounded-2xl! px-5! py-2! font-semibold! shadow-[0_10px_16px_rgba(0,0,0,0.25)]"
                                @click="leaveTheRoom"
                            />

                            <Button
                                v-if="!loading && authUser.user.id === room?.host?.id"
                                label="Start Match"
                                severity="success"
                                class="rounded-2xl! px-5! py-2! font-semibold! shadow-[0_10px_16px_rgba(0,0,0,0.25)]"
                                @click="start"
                            />
                            <!-- <Button label="Edit Match Rules" size="small" /> -->
                        </div>
                        <div v-else class="mt-4">
                            <p>Waiting for the host to start the match...</p>
                        </div>
                    </div>
                    
                </div>

                

                <div
                    v-if="loading"
                    class="mt-6 rounded-3xl bg-purple-300/35 p-6 text-center shadow-[0_15px_20px_rgba(0,0,0,0.28)]"
                >
                    <p>Loading...</p>
                </div>

                <div v-else class="mt-6">
                    <div class="rounded-3xl bg-purple-300/35 px-6 py-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:px-8">
                        <p class="mb-8 text-lg font-semibold md:text-xl">
                            Players in room: {{ numPlayers }}
                        </p>

                        <div class="flex min-h-[140px] flex-wrap items-start justify-center gap-x-10 gap-y-8">
                            <!-- Host -->
                            <div class="flex w-[100px] flex-col items-center text-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-white/50 bg-white/10 text-lg font-bold text-white">
                                    {{ getInitials(room?.host?.name) }}
                                </div>
                                <p class="mt-3 text-sm font-medium text-white">{{ room?.host?.name }}</p>
                                <p class="text-xs uppercase tracking-wider text-white/75">HOST</p>
                            </div>

                            <!-- Other players -->
                            <div
                                v-for="player in otherPlayers"
                                :key="player.id"
                                class="flex w-[100px] flex-col items-center text-center"
                            >
                                <div class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-white/50 bg-white/10 text-lg font-bold text-white">
                                    {{ getInitials(player.name) }}
                                </div>
                                <p class="mt-3 text-sm font-medium text-white">{{ player.name }}</p>

                                <div v-if="authUser.user.id === room?.host?.id" class="mt-2">
                                    <Button
                                        label="Make Owner"
                                        text
                                        class="rounded-xl! text-white! hover:bg-white/10!"
                                        @click="makePlayerOwner(player.id)"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EXTRA INFO -->
        <section class="px-4 pb-6">
            <div class="mx-auto max-w-5xl">
                <div v-if="loading" class="rounded-3xl bg-purple-300/35 p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)]">
                    <p>Loading...</p>
                </div>

                <div v-else class="rounded-3xl bg-purple-300/35 p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-8">
                    <h2 class="text-2xl font-bold md:text-3xl">
                        Game Mode
                    </h2>
                    <p class="mt-4 text-white/90">
                        Aqui anira una descripcio del game mode.
                    </p>
                </div>
            </div>
        </section>

        <!-- CHAT -->
        <section class="px-4 pb-8">
            <div class="mx-auto max-w-5xl rounded-3xl bg-purple-300/35 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-6">
                <Chat :roomId="id" />
            </div>
        </section>
    </div>
</template>

<script setup>
import { authStore } from "@/store/auth";
import { ref, onMounted, computed } from "vue";
import useRooms from "../../../composables/rooms";
import useGames from "../../../composables/games";
import { useRoute, useRouter } from "vue-router";
import Chat from "../../../components/roomComponents/Chat.vue";

const route = useRoute();
const router = useRouter();
const id = route.params.id;

const { getRoom, room, leaveRoom, transferOwnership, changePrivate } = useRooms();
const { startGame } = useGames();
const authUser = authStore();
const loading = ref(false);

onMounted(async () => {
    loading.value = true;

    try {
        await getRoom(id);

        window.Echo.join(`chat.room.${id}`)
            .here((users) => {
                console.log("Currently online:", users);
            })
            .joining((user) => {
                console.log(`${user.name} joined the room.`);
                getRoom(id);
            })
            .leaving((user) => {
                console.log(`${user.name} left the room.`);
                getRoom(id);
            })
            .error((error) => {
                console.error("Connection error:", error);
            });

        window.Echo.join(`game.room.${id}`)
            .listen("StartGame", (e) => {
                console.log(e.game_id);
                router.push({ name: "game", params: { id: e.game_id } });
            });
    } catch (error) {
        console.error("Failed to load Room:", error);
        router.push({ name: "rooms" });
    } finally {
        loading.value = false;
    }
});

const numPlayers = computed(() => {
    return room.value?.players?.length ?? 0;
});

const otherPlayers = computed(() => {
    return room.value?.players?.filter(
        (player) => player.id !== room.value?.host?.id
    ) ?? [];
});

const leaveTheRoom = async () => {
    if (!room.value?.id) return;

    await leaveRoom(room.value.id);
    window.Echo.leave(`chat.room.${room.value.id}`);
    window.Echo.leave(`game.room.${room.value.id}`);
    router.push({ name: "rooms" });
};

const makePlayerOwner = async (playerId) => {
    if (!room.value?.id) return;

    await transferOwnership(room.value.id, playerId);
    await getRoom(id);
};

const privateChange = async () => {
    if (!room.value?.id) return;

    await changePrivate(room.value.id);
    await getRoom(id);
};

const start = async () => {
    if (!room.value?.id) return;
    await startGame(room.value.id);
};

const getInitials = (name) => {
    if (!name) return "?";

    return name
        .split(" ")
        .map((word) => word[0])
        .join("")
        .slice(0, 2)
        .toUpperCase();
};
</script>