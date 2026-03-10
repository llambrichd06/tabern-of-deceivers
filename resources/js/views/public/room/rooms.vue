<template>
    <!-- ------------------------------ Menu Buttons ------------------------------ -->
    <section>
        <div class="">
            <Button label="Join Game by Code" />
            <Button label="Host Game" @click="hostGame"/>
            <Button label="Quick Match" />
        </div>
    </section>
    <section class="flex justify-center">
        <Card>
            <template #content>
            <DataView :value="rooms" paginator :rows="4"  :layout="'grid'">
                <template #header>
                    <h1>Avalible rooms</h1>
                    
                </template>
                <template #grid="slotProps">

                    <div class="grid grid-cols-12">
                        <div v-for="(item, index) in slotProps.items" class="col-span-12 sm:col-span-6 xl:col-span-6 p-2">
                            <div> 
                                <div>
                                    <div>Players: {{ item.players.length }}/6</div>
                                    <!-- <div>Mode: [mode]</div> -->
                                </div>
                                <div>
                                    <!-- We create the users with a loop. -->
                                    <div v-for="(player) in item.players">
                                        <img src="" alt="fotoPerfil">
                                        <p>{{ player.name }}</p>
                                    </div>
                                </div>
                                <div>
                                    <Button label="Join Room" /> 
                                </div>
                                <div>
                                    <p>host: {{item.host.name}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </DataView>
            </template>
        </Card>
    </section>
</template>

<script setup>
import { authStore } from "@/store/auth";
import { ref, onMounted } from "vue";
import useRooms from "../../../composables/rooms";

const { getOpenRooms, rooms, storeRoom, room} = useRooms();
const auth = authStore();
onMounted(async () => {
    getOpenRooms();
});

const hostGame = async () => {
    console.log('start');
    room.value.host_id = auth.user.id;
    room.value.private = true;
    room.value.state = 'lobby';
    console.log(room.value);
    await storeRoom(room.value); 
    console.log('end');
}

</script>
