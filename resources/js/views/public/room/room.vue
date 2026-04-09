<template>
    <section class="px-6 py-6">
        <!-- Opcions de les sales -->
        <div class="mx-auto max-w-5xl">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-10">
                    <p class="text-3xl font-normal">Room code: {{ room.room_code }}</p>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-normal">
                                {{ room.private == false ? 'Public' : 'Private' }}
                            </p>

                            <Image
                                v-if="room.private == false"
                                src=""
                                alt="unlockRoom"
                            />
                            <Image
                                v-else
                                src=""
                                alt="lockRoom"
                            />
                        </div>

                        <div v-if="!loading && authUser.user.id == room.host?.id">
                            <Button
                                v-if="room.private == true"
                                label="Unprivate the room"
                                outlined
                                @click="privateChange"
                            />
                            <Button
                                v-if="room.private == false"
                                label="Private the room"
                                outlined
                                @click="privateChange"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <Button label="Edit Match Rules" size="small" />

                    <!-- GOTTA MAKE AN "Are you sure?" WINDOW FOR THIS, MAKE IT SO IT CAN BE REUSED WITH OTHER STUFF -->
                    <Button label="Leave Room" severity="danger" size="small" @click="leaveTheRoom" />

                    <Button label="Start Match" severity="success" size="small" @click="start" />
                </div>
            </div>

            <div v-if="loading" class="mt-6">
                <p>Loading...</p>
            </div>

            <div v-else class="mt-6">
                <div class="bg-gray-200 px-8 py-6">
                    <p class="mb-6 text-lg font-medium">Players in room: {{ numPlayers }}</p>

                    <div
                        class="flex min-h-[140px] flex-wrap items-start justify-center gap-x-10 gap-y-8"
                    >
                        <!-- Host -->
                        <div class="flex w-[90px] flex-col items-center text-center">
                            <div class="h-16 w-16 rounded-full border-4 border-black"></div>
                            <p class="mt-3 text-sm">{{ room.host?.name }}</p>
                            <p class="text-xs uppercase">HOST</p>
                        </div>

                        <!-- El host mai es repetira i es mostren els altres usuaris -->
                        <div
                            v-for="player in otherPlayers"
                            :key="player.id"
                            class="flex w-[90px] flex-col items-center text-center"
                        >
                            <div class="h-16 w-16 rounded-full border-4 border-black"></div>
                            <p class="mt-3 text-sm">{{ player.name }}</p>

                            <div v-if="authUser.user.id == room.host.id" class="mt-2">
                                <Button
                                    label="Make Owner"
                                    size="small"
                                    text
                                    @click="makePlayerOwner(player.id)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6">
        <div class="mx-auto max-w-5xl">
            <!-- bucle per els altres jugadors que es igual que el anterior -->
            <div v-if="loading"><p>Loading...</p></div>
            <br><br>
        </div>
    </section>

    <section class="px-6">
        <div class="mx-auto max-w-5xl">
            <!-- Descripcio i gameplay -->
            <!-- Agafarem una descripcio de una taula si ho acabem fent el seguent text es un exemplpe que s'ha de modificar-->
            <h2>Aqui anira una descripcio del game mode</h2>
        </div>
    </section>

    <section>
        <Chat :roomId="id"/>
    </section>
</template>

<script setup>
import { authStore } from "@/store/auth";
import { ref, onMounted, onBeforeUnmount } from "vue";
import useRooms from "../../../composables/rooms";
import useGames from "../../../composables/games";
import { useRoute } from 'vue-router'
import { computed } from "vue";
import axios from "axios";
import Chat from '../../../components/roomComponents/Chat.vue'

const route = useRoute()
const id = route.params.id

// console.log('id de sala: ' + id)

const { getRoom, room, leaveRoom, transferOwnership, changePrivate } = useRooms();
const { startGame } = useGames();
const authUser = authStore();
const loading = ref(false);

onMounted(async () => {
    loading.value = true;
    try {
        await getRoom(id);

        // console.log(room.value);
        
        window.Echo.join(`chat.room.${id}`)
            .here((users) => {
                // Runs immediately: gives you a list of everyone currently in the room
                console.log('Currently online:', users);
            })
            .joining((user) => {
                // Runs when a new person joins
                console.log(`${user.name} joined the room.`);
                getRoom(id);
            })
            .leaving((user) => {
                // Runs when someone closes the tab or disconnects
                console.log(`${user.name} left the room.`);
                getRoom(id);
            })
            .error((error) => {
                console.error('Connection error:', error);
            })
            .listen('StartGame', (e) => {
                // Standard event listener for messages within that room
                console.log(e.game_id);
                route.push({ name: 'game', params: { id: e.game_id } })

            });
        
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

const otherPlayers = computed(() => {
    return room.value?.players?.filter(player => player.id !== room.value?.host?.id) ?? [];
});

const leaveTheRoom = async () => {
    await leaveRoom(room.value.id);
    window.Echo.leave(`chat.room.${room.value.id}`)

};

const makePlayerOwner = async(player_id) => {
    await transferOwnership(room.value.id, player_id);
    await getRoom(id);
}

const privateChange = async() => {
    await changePrivate(room.value.id);
    await getRoom(id);
}

const start = () => {
    startGame(room.value.id)
}
</script>