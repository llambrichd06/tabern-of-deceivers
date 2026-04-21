<template>
    <!-- @show is the event triggered when the dialog is made visible -->
    <Dialog :visible="visible" modal :closable="false" class="w-220" @show="getPlayerArraySorted"> 
        <template #header>
            <h2 class="flex justify-center w-full text-3xl">The game has ended</h2>
        </template>
        <template #default>
            <div class="flex justify-between items-start">
                <div class="flex flex-col justify-between items-center gap-5 flex-1"> <!-- Only putting one of each value because we'll loop it later somehow. -->
                    <h2 class="text-2xl">Position</h2>
                    <div v-for="(player, index) in playerArraySorted">
                        <div class="flex justify-center items-center ">
                            <p>{{ index+1 }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-between items-center gap-5 flex-3">
                    <h2 class="text-2xl">Players</h2>
                    <div v-for="(player, index) in playerArraySorted" class="w-full">
                        <div class="flex justify-around gap-3">
                            <div class="flex justify-center items-center">
                                <Image src="" alt="pfp"/>
                            </div>
                            <div class="flex flex-col justify-center items-center ">
                                <p class="">{{player.name}}</p>
                                <p v-if="index == 0">WINNER!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-between items-center gap-5 flex-1">
                    <h2 class="text-2xl">Cards left</h2>
                    <div v-for="player in playerArraySorted">
                        <div class="flex justify-center items-center">
                            {{ player.cardCount == 0 ? 'None!' : player.cardCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center mt-10 gap-5">
                <div class="flex justify-center">
                    <p class="text-3xl">Stay in the room?</p>
                </div>
                <div class="flex justify-around gap-2 w-full">
                    <Button label="Stay" severity="primary" @click="stayInRoom" />
                    <Button label="Cancel" severity="danger" @click="leaveTheRoom" />
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';
    import useRooms from '../../composables/rooms';
    import useGames from '../../composables/games';

    const { getRoom, leaveRoom, room } = useRooms();

    const router = useRouter();
    const visible = defineModel<any>('visible');
	const game = defineModel<any>('game');

    const playerArraySorted = ref([])
    
    const getPlayerArraySorted = async () => {
        const playersDecks = game.value.game_state.player_decks
        const players = game.value.game_state.players
        playerArraySorted.value = []
        console.log('the helly?');
        console.log(game.value);
        

        await getRoom(game.value.room_id)
        players.forEach((playerId, index) => {
            room.value.players.forEach(playerObj => {
                if (playerObj.id == playerId) {
                    playerObj.playerNum = index;
                    playerObj.cardCount = playersDecks['player'+(index+1)].count
                    playerArraySorted.value.push(playerObj)
                }
            });
        });
        playerArraySorted.value.sort((a, b) => {
            return a.cardCount-b.cardCount
        })
    }

    const stayInRoom = () => {
        router.push({ name: 'lobby', params: { id: game.value.room_id } });
    }

    const leaveTheRoom = async () => {
        window.Echo.leave(`chat.room.${game.value.room_id}`)
        await leaveRoom(game.value.room_id);
    };
</script>

<style lang="scss" scoped>

</style>