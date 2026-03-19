<template>
    <section>
        <!-- Opcions de les sales -->
        <p>Room code: {{room.room_code}}</p>
        <div v-if="room.private == false">
            <p>Public</p>
            <Image src="" alt="unlockRoom"/>
        </div>
        <div v-else>
            <p>Private</p>
            <Image src="" alt="lockRoom"/>
        </div>
        <Button label="Edit Match Rules"/>
        <Button label="Leave Room" @click="leaveTheRoom"/>
        <Button label="Start Match"/>
    </section>
    <section>
        
       
        <!-- bucle per els altres jugadors que es igual que el anterior -->
        <div v-if="loading"><p>Loading...</p></div>
        <div v-else>
            <p>number of players: {{ numPlayers }}</p>
            <ul><!-- Usuaris i host -->
                <li> <!--El host sempre sortira primer i tindra el "titol" de host-->
                    <p>{{room.host?.name}}</p>
                    <p>host</p>
                </li>
                <!--El host mai es repetira i es mostren els altres usuaris-->
                <li 
                    v-for="(player, index) in room.players" 
                    :key="player.id"
                >
                    <p v-if="player.id !== room.host.id">
                        {{ player.name }}
                    </p>
                    
                </li>
            </ul>
        </div>
        <br><br>
    </section>
    <section>
        <!-- Descripcio i gameplay -->
        <!-- Agafarem una descripcio de una taula si ho acabem fent el seguent text es un exemplpe que s'ha de modificar-->
        <h2>Aqui anira una descripcio del game mode</h2>
    </section>
    <section>
        <!-- chat -->

    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import useRooms from "../../../composables/rooms";
import { useRoute } from 'vue-router'
import { computed } from "vue";

const route = useRoute()
const id = route.params.id
// console.log('id de sala: ' + id)

const { getRoom, room, leaveRoom } = useRooms();
const loading = ref(false);

onMounted(async () => {
    loading.value = true;

    try {
        await getRoom(id);

        // console.log(room.value);

    } catch (error) {
        console.error("Failed to load Room: ", error);
    } finally {
        loading.value = false;
    }
});

const numPlayers = computed(() => {
    const players = room.value?.players?.length ?? 0;
    return players;
});

const leaveTheRoom = async () => {
  await leaveRoom(room.value.id);
};
</script>