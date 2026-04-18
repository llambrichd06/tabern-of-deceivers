<template>
    <div v-if="game.game_state && game.room_id">
        <section> <!--Game-->
            <GameComponent v-model:game="game"/>
            <p>{{ game.game_state }}</p>
        </section>
        <section>
            <Chat :roomId="game.room_id"/>
            <p>{{ game.room_id }}</p>
        </section>
        {{isLoading}}
    </div>
    <div v-else class="flex justify-center w-full pt-8">
        <p class="text-5xl">Loading...</p>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeMount, onUnmounted } from "vue";
import Chat from "../../../components/roomComponents/Chat.vue";
import GameComponent from './GameComponent.vue'
import { useRoute, useRouter } from 'vue-router'
import useGames from "../../../composables/games";

const route = useRoute()
const router = useRouter()
const { game, getGame, isLoading } = useGames()
const gameId = route.params.id

onBeforeMount(async () => {
    await getGame(gameId)
    window.Echo.join(`game.room.${game.value.room_id}`)
            .here((users) => {
                // Runs immediately: gives you a list of everyone currently in the room
                console.log('Currently loaded:', users);
            })
            .listen('UpdateGameState', async (e) => {
                
                console.log('GAME UPDATED! GET MY STATE');
                await getGame(gameId);
            })
            .listen('CardPlayed', (e) => {
                
                console.log('CARD PLAYED, PAUSE FOR A SECOND');
                isLoading.value = true;
            })
            .listen('LieCalled', (e) => {
                
                console.log('LIE CALLED, PAUSE FOR A SECOND');
                isLoading.value = true;
            })
            .listen('TakenCards', (e) => {
                console.log("CARDS WERE TAKEN, THE RESULT IS... "+e.result);
            })
            .listen('GameWon', (e) => {
                
                console.log('GAME WIN WOW');
            });;
});

onUnmounted(()=>{
    window.Echo.join(`game.room.${game.value.room_id}`)
    .stopListening('UpdateGameState')
    .stopListening('CardPlayed')
    .stopListening('LieCalled')
    .stopListening('TakenCards')
    .stopListening('GameWon');
})
// onMounted(async() => {
// })

// onMounted(async () => {
//     loading.value = true;
//     try {
//     } catch (error) {

//     }finally {
//         loading.value = false;
//     }
// });

</script>
