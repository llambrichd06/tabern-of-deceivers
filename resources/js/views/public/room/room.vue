<template>
    <div class="min-h-screen bg-[#520B93] text-white">
        <!-- TOP SECTION -->
        <section class="px-4 pt-2 pb-10 md:pt-1 md:pb-14">
            <div class="mx-auto max-w-7xl">
                <!-- HEADER -->
                <div class="mb-10 text-center">
                    <h1 class="mb-6 text-4xl font-extrabold tracking-tight md:text-6xl">
                        Room Lobby
                    </h1>

                    <p class="mx-auto max-w-3xl text-base leading-7 text-white/90 md:text-lg">
                        Wait for players to join, manage the room settings, and start the match when everyone is ready.
                    </p>
                </div>

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

                        <div class="flex flex-wrap items-center gap-2">
                            <Button
                                v-if="!loading && authUser.user.id === room?.host?.id"
                                label="Start Match"
                                severity="success"
                                class="rounded-2xl! px-5! py-2! font-semibold! shadow-[0_10px_16px_rgba(0,0,0,0.25)]"
                                @click="start"
                            />
                            <div v-else>
                                <p>Waiting for the host to start the match...</p>
                            </div>

                            <Button
                                label="Leave Room"
                                severity="danger"
                                class="rounded-2xl! px-5! py-2! font-semibold! shadow-[0_10px_16px_rgba(0,0,0,0.25)]"
                                @click="leaveTheRoom"
                            />
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
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1fr)_340px] xl:grid-cols-[minmax(0,1fr)_360px]">
                        <!-- PLAYERS LEFT -->
                        <div class="rounded-3xl bg-purple-300/35 px-6 py-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:px-8 lg:min-h-[560px]">
                            <div class="mb-8 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <p class="text-lg font-semibold md:text-xl">
                                    Players in room: {{ numPlayers }}
                                </p>

                                <p class="text-sm text-white/75">
                                    Max 6 players
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 xl:grid-cols-3">
                                <!-- Host -->
                                <div class="flex min-h-[150px] flex-col items-center rounded-2xl bg-white/8 px-3 py-4 text-center ring-2 ring-yellow-300/60">
                                    <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full border-2 border-white/50 bg-white/10 text-lg font-bold text-white">
                                        <img
                                            v-if="room?.host"
                                            :src="getUserAvatar(room.host)"
                                            :alt="room.host.name ?? 'Host avatar'"
                                            class="h-full w-full object-cover"
                                            @error="handleAvatarError"
                                        />

                                        <span v-else>
                                            {{ getInitials(room?.host?.name) }}
                                        </span>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-white break-words">
                                        {{ room?.host?.name }}
                                    </p>

                                    <p class="mt-1 text-xs uppercase tracking-wider text-yellow-200">
                                        Host
                                    </p>
                                </div>

                                <!-- Other players -->
                                <div
                                    v-for="player in otherPlayers"
                                    :key="player.id"
                                    class="flex min-h-[150px] flex-col items-center rounded-2xl bg-white/8 px-3 py-4 text-center"
                                >
                                    <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full border-2 border-white/50 bg-white/10 text-lg font-bold text-white">
                                        <img
                                            :src="getUserAvatar(player)"
                                            :alt="player.name ?? 'Player avatar'"
                                            class="h-full w-full object-cover"
                                            @error="handleAvatarError"
                                        />
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-white break-words">
                                        {{ player.name }}
                                    </p>

                                    <div v-if="authUser.user.id === room?.host?.id" class="mt-3">
                                        <Button
                                            label="Make Owner"
                                            text
                                            class="rounded-xl! text-white! hover:bg-white/10!"
                                            @click="makePlayerOwner(player.id)"
                                        />
                                    </div>
                                </div>

                                <!-- Empty slots -->
                                <div
                                    v-for="slot in emptySlots"
                                    :key="`empty-${slot}`"
                                    class="flex min-h-[150px] flex-col items-center justify-center rounded-2xl border border-dashed border-white/25 bg-white/5 px-3 py-4 text-center"
                                >
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-dashed border-white/30 text-2xl text-white/40">
                                        +
                                    </div>

                                    <p class="mt-3 text-sm font-medium text-white/50">
                                        Empty slot
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- CHAT RIGHT -->
                        <div class="rounded-3xl bg-purple-300/35 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-5 lg:h-[560px] overflow-hidden">
                            <div class="mb-3 flex items-center justify-between px-1">
                                <h2 class="text-xl font-bold text-white">
                                    Chat
                                </h2>
                                <p class="text-sm text-white/70">
                                    Room {{ room?.room_code }}
                                </p>
                            </div>

                            <div class="h-[calc(100%-2.5rem)] overflow-hidden rounded-2xl bg-black/10 p-2">
                                <Chat :roomId="id" />
                            </div>
                        </div>
                    </div>
                </div>
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

const fallbackAvatar = "/images/placeholder.png";

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

const emptySlots = computed(() => {
    const totalSlots = 6;
    return Math.max(totalSlots - numPlayers.value, 0);
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

const getUserAvatar = (user) => {
    return user?.avatar || fallbackAvatar;
};

const handleAvatarError = (event) => {
    event.target.src = fallbackAvatar;
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