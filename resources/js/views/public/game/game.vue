<template>
    <div class="page-content min-h-screen bg-[#520B93] text-white">
        <div v-if="game.game_state && game.room_id">

            <!-- GAME + CHAT -->
            <section class="px-4 py-8">
                <div class="mx-auto w-full max-w-5xl flex flex-col gap-6">

                    <!-- GAME -->
                    <div class="rounded-3xl bg-purple-300/35 p-4 md:p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)]">
                        <div class="rounded-2xl bg-emerald-900/80 p-4">
                            <GameComponent v-model:game="game" v-model:loading="isLoading"/>
                        </div>
                    </div>

                    <!-- CHAT -->
                    <div class="rounded-3xl bg-purple-300/35 p-4 md:p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)]">
                        <!-- <h2 class="text-2xl font-bold text-center mb-4">Chat</h2> -->
                        <!-- <div class="rounded-2xl bg-emerald-900/60 p-4"> -->
                        <Chat :roomId="game.room_id"/>
                        <!-- </div> -->
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

        <Button label="simulate game win" @click="gameWon = true"/>
    </div>
</template>

<script setup>
import { onBeforeMount, onUnmounted, ref } from "vue";
import Chat from "../../../components/roomComponents/Chat.vue";
import GameComponent from '../../../components/gameComponent/GameComponent.vue'
import { useRoute } from 'vue-router'
import useGames from "../../../composables/games";
import GameResult from "../../../components/gameComponent/GameResult.vue";

const route = useRoute()
const { game, getGame, isLoading } = useGames()
const gameId = route.params.id
const gameWon = ref(false)

onBeforeMount(async () => {
    await getGame(gameId)
    window.Echo.join(`game.room.${game.value.room_id}`)
        .here((users) => {
            console.log('Currently loaded:', users);
        })
        .listen('UpdateGameState', async () => {
            console.log('GAME UPDATED! GET MY STATE');
            await getGame(gameId).then(isLoading.value = false);
        })
        .listen('CardPlayed', () => {
            console.log('CARD PLAYED, PAUSE FOR A SECOND');
            isLoading.value = true;
        })
        .listen('LieCalled', () => {
            console.log('LIE CALLED, PAUSE FOR A SECOND');
            isLoading.value = true;
        })
        .listen('TakenCards', (e) => {
            console.log("CARDS WERE TAKEN, THE RESULT IS... "+e.result);
        })
        .listen('GameWon', () => {
            console.log('GAME WIN WOW');
            window.Echo.leave(`game.room.${game.value.room_id}`)
            gameWon.value = true;
        });
});

onUnmounted(()=>{
    // 🔒 tu lógica intacta
    window.Echo.join(`game.room.${game.value.room_id}`)
    .stopListening('UpdateGameState')
    .stopListening('CardPlayed')
    .stopListening('LieCalled')
    .stopListening('TakenCards')
    .stopListening('GameWon');
})
</script>