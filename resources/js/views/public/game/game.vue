<template>
    <div class="page-content min-h-screen bg-[#520B93] text-white">
        <div v-if="game.game_state && game.room_id">
            <!-- GAME + CHAT -->
            <section class="px-4 py-2 md:py-1">
                <div class="mx-auto w-full max-w-7xl">
                    <div
                        class="grid grid-cols-1 items-start gap-4 lg:grid-cols-[minmax(0,1fr)_340px]"
                    >
                        <!-- GAME -->
                        <div
                            class="flex w-full flex-col rounded-3xl bg-purple-300/35 p-3 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-4"
                        >
                            <div class="flex w-full flex-col rounded-2xl bg-emerald-900/80 p-3 md:p-4">
                                <GameComponent v-model:game="game" v-model:loading="isLoading" />
                            </div>
                        </div>

                        <!-- CHAT -->
                        <div
                            class="flex h-full min-h-0 flex-col overflow-hidden rounded-3xl bg-purple-300/35 p-3 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-4"
                        >
                            <div class="mb-3 flex items-center justify-between px-1">
                                <h2 class="text-xl font-bold text-white"> Chat </h2>
                                <p v-if="room.room_code" class="text-sm text-white/70"> Room {{ room.room_code }} </p>
                            </div>

                            <div class="flex h-full min-h-0 flex-col overflow-hidden rounded-2xl p-2 md:p-3">
                                <Chat :roomId="game.room_id" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- LOADING -->
        <div v-else class="flex min-h-screen items-center justify-center px-4">
            <div class="rounded-3xl bg-purple-300/35 p-8 text-center shadow-[0_15px_20px_rgba(0,0,0,0.28)]">
                <div
                    class="rounded-2xl bg-cover bg-repeat p-8"
                    style="background-image: url('/img/backCard.png');"
                >
                    <p class="text-4xl font-bold">Loading...</p>
                </div>
            </div>
        </div>

        <GameResult v-model:visible="gameWon" v-model:game="game" />
        <QuitGame v-if="!gameWon"/>
        <!-- <Button label="simulate game win" @click="gameWon = true" /> -->
    </div>
</template>

<script setup>
import { onBeforeMount, onUnmounted, ref } from "vue";
import Chat from "../../../components/roomComponents/Chat.vue";
import GameComponent from "../../../components/gameComponent/GameComponent.vue";
import { useRoute } from "vue-router";
import GameResult from "../../../components/gameComponent/GameResult.vue";
import QuitGame from "../../../components/gameComponent/QuitGame.vue";
import useRooms from "../../../composables/rooms";
import useGames from "../../../composables/games";

const route = useRoute();

const { room, getRoom } = useRooms();

const { game, getGameState, isLoading } = useGames();
const gameId = route.params.id;
const gameWon = ref(false);

onBeforeMount(async () => {
    await getGameState(gameId);
    await getRoom(game.value.room_id)
    window.Echo.join(`game.room.${game.value.room_id}`)
        .here((users) => {
            console.log("Currently loaded:", users);
        })
        .listen("UpdateGameState", async () => {
            console.log("GAME UPDATED! GET MY STATE");
            await getGameState(gameId).then(isLoading.value = false);
        })
        .listen("CardPlayed", () => {
            console.log("CARD PLAYED, PAUSE FOR A SECOND");
            isLoading.value = true;
        })
        .listen("LieCalled", () => {
            console.log("LIE CALLED, PAUSE FOR A SECOND");
            isLoading.value = true;
        })
        .listen("TakenCards", (e) => {
            console.log("CARDS WERE TAKEN, THE RESULT IS... " + e.result);
        })
        .listen("GameWon", () => {
            console.log("GAME WIN WOW");
            window.Echo.leave(`game.room.${game.value.room_id}`);
            gameWon.value = true;
        });
});

onUnmounted(() => {
    window.Echo.join(`game.room.${game.value.room_id}`)
        .stopListening("UpdateGameState")
        .stopListening("CardPlayed")
        .stopListening("LieCalled")
        .stopListening("TakenCards")
        .stopListening("GameWon");
});
</script>