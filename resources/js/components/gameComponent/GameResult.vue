<template>
    <Dialog
        :visible="visible"
        modal
        :closable="false"
        class="game-result-dialog w-[95vw] max-w-4xl"
        @show="getPlayerArraySorted"
    >
        <template #header>
            <div class="w-full text-center">
                <h2 class="text-2xl md:text-3xl font-extrabold text-white">
                    Game Over
                </h2>
            </div>
        </template>

        <template #default>
            <div class="text-white">
                <!-- Winner -->
                <div
                    v-if="playerArraySorted.length > 0"
                    class="mb-4 rounded-2xl bg-purple-300/30 p-4 shadow-[0_10px_15px_rgba(0,0,0,0.25)]"
                >
                    <div class="flex flex-col items-center text-center">
                        <img src="/images/crown.svg" class="mb-2 h-8 w-8" />

                        <p class="text-xs font-semibold uppercase tracking-widest text-yellow-200">
                            Winner
                        </p>

                        <h3 class="text-xl md:text-2xl font-bold text-yellow-300">
                            {{ playerArraySorted[0].name }}
                        </h3>

                        <p class="text-sm text-white/90">
                            {{
                                playerArraySorted[0].cardCount === 0
                                    ? 'No cards left'
                                    : playerArraySorted[0].cardCount + ' cards left'
                            }}
                        </p>
                    </div>
                </div>

                <!-- Rankings -->
                <div class="rounded-2xl bg-purple-300/25 p-3 shadow-[0_10px_15px_rgba(0,0,0,0.25)]">
                    
                    <!-- HEADER -->
                    <div class="flex items-center justify-between px-3 pb-2 text-sm font-bold text-white/80 border-b border-white/20">
                        <span class="w-20 text-center">Rank</span>
                        <span class="flex-1 text-center">User</span>
                        <span class="w-20 text-right">Cards</span>
                    </div>

                    <!-- Players -->
                    <div class="flex flex-col gap-2 mt-2">
                        <div
                            v-for="(player, index) in playerArraySorted"
                            :key="player.id ?? index"
                            class="flex items-center justify-between rounded-xl border border-white/15 bg-purple-200/20 px-3 py-2"
                            :class="index === 0 ? 'ring-2 ring-yellow-300/70' : ''"
                        >
                            <!-- Rank -->
                            <div class="w-20 text-center font-bold">
                                {{ index + 1 }}
                            </div>

                            <!-- User -->
                            <div class="flex-1 text-center font-semibold truncate">
                                {{ player.name }}
                            </div>

                            <!-- Cards -->
                            <div class="w-20 text-right font-semibold">
                                {{ player.cardCount === 0 ? 'None' : player.cardCount }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-4 flex flex-col items-center gap-3">
                    <p class="text-lg font-semibold">
                        Stay in the room?
                    </p>

                    <div class="flex gap-3">
                        <Button
                            label="Stay"
                            severity="secondary"
                            class="rounded-xl! px-6! py-2!"
                            @click="stayInRoom"
                        />
                        <Button
                            label="Leave"
                            severity="danger"
                            class="rounded-xl! px-6! py-2!"
                            @click="leaveTheRoom"
                        />
                    </div>
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import useRooms from '../../composables/rooms';

const { getRoom, leaveRoom, room } = useRooms();

const router = useRouter();
const visible = defineModel<any>('visible');
const game = defineModel<any>('game');

const playerArraySorted = ref<any[]>([]);

const getPlayerArraySorted = async () => {
    const playersDecks = game.value.game_state.player_decks;
    const players = game.value.game_state.players;

    playerArraySorted.value = [];

    await getRoom(game.value.room_id);

    players.forEach((playerId, index) => {
        room.value.players.forEach((playerObj) => {
            if (playerObj.id == playerId) {
                playerArraySorted.value.push({
                    ...playerObj,
                    playerNum: index + 1,
                    cardCount: playersDecks['player' + (index + 1)].count
                });
            }
        });
    });

    playerArraySorted.value.sort((a, b) => a.cardCount - b.cardCount);
};

const stayInRoom = () => {
    router.push({ name: 'lobby', params: { id: game.value.room_id } });
};

const leaveTheRoom = async () => {
    window.Echo.leave(`chat.room.${game.value.room_id}`);
    await leaveRoom(game.value.room_id);
};
</script>

<style>
.game-result-dialog.p-dialog {
    background: #520b93 !important;
    color: white !important;
    border: none !important;
    border-radius: 1.5rem !important;
    overflow: hidden !important;
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.35) !important;
}

.game-result-dialog .p-dialog-header {
    background: #520b93 !important;
    color: white !important;
    border: none !important;
    padding: 1rem 1rem 0.75rem 1rem !important;
}

.game-result-dialog .p-dialog-content {
    background: #520b93 !important;
    color: white !important;
    padding: 1rem !important;
}

.game-result-dialog .p-dialog-footer {
    background: #520b93 !important;
    color: white !important;
    border: none !important;
}

.p-dialog-mask {
    backdrop-filter: blur(4px);
}
</style>