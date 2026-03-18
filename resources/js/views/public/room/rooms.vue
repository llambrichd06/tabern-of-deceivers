<template>
    <!-- ------------------------------ Menu Buttons ------------------------------ -->
    <section>
        <div class="">
            <Button label="Join Game by Code" @click="openJoinGame"/>
            <Button label="Host Game" @click="hostGame"/>
            <Button label="Quick Match" @click="searchQuickGame"/>
        </div>
    </section>
    <section class="flex justify-center">
        <Card>
            <template #content>
                <DataView :value="rooms" paginator :rows="4"  :layout="'grid'">
                    <template #header>
                        <h1>Available rooms</h1>
                    </template>
                    <template #grid="slotProps">
                        <div class="grid grid-cols-12">
                            <div v-for="(item, index) in slotProps.items" class="col-span-12 sm:col-span-6 xl:col-span-6 p-2">
                                <div> 
                                    <div>
                                        <div>Players: {{ item.players.length }}/6</div>
                                    </div>
                                    <div>
                                        <div v-for="(player) in item.players" :key="player.id">
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

        <!-- El visible es qui mana si es veu o no -->
    
    <!----------------- QUICK PLAY DIALOG WINDOW ------------------>
    <WaitingQuickPlay  
        v-model:visible="showQuickPlayDialog" 
    />
    <!----------------- JOIN ROOM DIALOG WINDOW ------------------>
    <JoinRoom v-model:visible="joinRoomVisible"/>

    
</template>

<script setup>
import { authStore } from "@/store/auth";
import { ref, onMounted } from "vue";
import useRooms from "/resources/js/composables/rooms.js";
import WaitingQuickPlay from "../../../components/roomComponents/WaitingQuickPlay.vue";
import JoinRoom from "../../../components/roomComponents/JoinRoom.vue";

const { getOpenRooms, rooms, storeRoom, room } = useRooms();

const showQuickPlayDialog = ref(false);
const joinRoomVisible = ref(false);
const auth = authStore();

onMounted(async () => {
    getOpenRooms();
});

const hostGame = async () => {
    room.value.host_id = auth.user.id;
    room.value.private = true;
    room.value.state = 'lobby';
    await storeRoom(room.value); 
}


const searchQuickGame = () => {
    showQuickPlayDialog.value = true;
}

const openJoinGame = () => {
    joinRoomVisible.value = true;
}
</script>
