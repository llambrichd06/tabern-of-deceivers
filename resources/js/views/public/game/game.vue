<template>
    <div v-if="game.game_state && game.room_id">
        <section> <!--Game-->
            <GameComponent v-model:game="game" v-model:loading="isLoading"/>
        </section>
        <section>
            <Chat :roomId="game.room_id"/>
        </section>
    </div>
    <div v-else class="flex justify-center w-full pt-8">
        <p class="text-5xl">Loading...</p>
    </div>
    <GameResult v-model:visible="gameWon" v-model:game="game" /> 
    <Button label="simulate game win" @click="gameWon = true"/>
</template>

<script setup>
import { onBeforeMount, onUnmounted, ref } from "vue";
import Chat from "../../../components/roomComponents/Chat.vue";
import GameComponent from '../../../components/gameComponent/GameComponent.vue'
import { useRoute, useRouter } from 'vue-router'
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
                // Runs immediately: gives you a list of everyone currently in the room
                console.log('Currently loaded:', users);
            })
            .listen('UpdateGameState', async (e) => {
                
                console.log('GAME UPDATED! GET MY STATE');
                await getGame(gameId).then(isLoading.value = false);
                console.log('WHY DOES IT STAY AS TRUE '+isLoading.value);
                
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
                window.Echo.leave(`game.room.${game.value.room_id}`)
                gameWon.value = true;
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
