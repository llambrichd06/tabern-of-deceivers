<template>
    <div class="page-content min-h-screen bg-[#520B93] text-white">
        <div v-if="game.game_state && game.room_id">
            <!-- GAME + CHAT -->
            <section class="px-4 py-4 lg:py-6">
                <div class="mx-auto w-full max-w-7xl">
                    <div
                        class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1fr)_320px] xl:grid-cols-[minmax(0,1fr)_340px] items-stretch"
                    >
                        <!-- GAME -->
                        <div
                            class="rounded-3xl bg-purple-300/35 p-3 md:p-4 shadow-[0_15px_20px_rgba(0,0,0,0.28)] lg:h-[calc(100vh-3rem)] overflow-hidden"
                        >
                            <div class="h-full rounded-2xl bg-emerald-900/80 p-3 md:p-4 overflow-auto">
                                <GameComponent v-model:game="game" v-model:loading="isLoading" />
                            </div>
                        </div>

                        <!-- CHAT -->
                        <div
                            class="rounded-3xl bg-purple-300/35 p-3 md:p-4 shadow-[0_15px_20px_rgba(0,0,0,0.28)] lg:h-[calc(100vh-3rem)] overflow-hidden"
                        >
                            <div class="h-full rounded-2xl bg-black/10 p-2 md:p-3 overflow-hidden">
                                <Chat :roomId="game.room_id" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- LOADING -->
        <div v-else class="flex justify-center items-center min-h-screen px-4">
            <div class="rounded-3xl bg-purple-300/35 p-8 shadow-[0_15px_20px_rgba(0,0,0,0.28)] text-center">
                <div
                    class="rounded-2xl bg-cover bg-repeat p-8"
                    style="background-image: url('/img/backCard.png');"
                >
                    <p class="text-4xl font-bold">Loading...</p>
                </div>
            </div>
        </div>

        <GameResult v-model:visible="gameWon" v-model:game="game" />

        <Button label="simulate game win" @click="gameWon = true" />
    </div>
</template>

<script setup>
import { onBeforeMount, onUnmounted, ref } from "vue";
import Chat from "../../../components/roomComponents/Chat.vue";
import GameComponent from "../../../components/gameComponent/GameComponent.vue";
import { useRoute } from "vue-router";
import useGames from "../../../composables/games";
import GameResult from "../../../components/gameComponent/GameResult.vue";

const route = useRoute();
const { game, getGame, isLoading } = useGames();
const gameId = route.params.id;
const gameWon = ref(false);

onBeforeMount(async () => {
    await getGame(gameId);
    window.Echo.join(`game.room.${game.value.room_id}`)
        .here((users) => {
            console.log("Currently loaded:", users);
        })
        .listen("UpdateGameState", async () => {
            console.log("GAME UPDATED! GET MY STATE");
            await getGame(gameId).then(isLoading.value = false);
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