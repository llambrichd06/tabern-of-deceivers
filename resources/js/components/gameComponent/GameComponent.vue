<template>
    <div class="flex justify-center gap-4">
        <!-- Up row -->
        <div
            v-for="(decks, player, index) in otherPlayerDecks"
            :key="player"
            :class="{ 'hidden': player === 'player' + myPlayerNum || decks.count < 1 || turnOf == lastPlayerTurn }"
        >
            <div class="p-4 w-full flex flex-col items-center justify-center">
                <div class="flex flex-col items-center gap-2">
                    <div class="text-center text-lg font-semibold">
                        {{ getPlayerName(index) }}
                    </div>

                    <div class="flex flex-col items-center">
                        <Image
                            src="/images/Cards/backCard.png"
                            alt="Back of the Card"
                            imageClass="w-20 h-30 rounded-md object-cover"
                        />
                        <p class="mt-2 text-center text-base font-medium">
                            Total cards: {{ decks.count }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center my-20 w-full items-center gap-6">
        <!-- Left info -->
        <div class="flex-1 flex justify-start">
            <div class="w-56 min-h-24 flex flex-col justify-center">
                <p class="text-left text-xl md:text-2xl font-semibold">Turn: {{ turn }}</p>
                <p v-if="myPlayerNum == turnOf" class="text-left text-xl md:text-2xl font-semibold">
                    Your turn
                </p>
                <p v-else class="text-left text-xl md:text-2xl font-semibold">
                    Player {{ turnOf }}'s turn
                </p>
                <p class="text-left text-xl md:text-2xl font-semibold">
                    You are Player {{ myPlayerNum }}
                </p>
            </div>
        </div>

        <!-- Middle pile -->
        <div class="flex flex-col items-center justify-center gap-3">
            <div class="flex items-center justify-center gap-4">
                <div class="w-20 h-30 flex items-center justify-center">
                    <Image
                        src="/images/Cards/backCard.png"
                        alt="Back of the Card"
                        imageClass="w-20 h-30 rounded-md object-cover"
                    />
                </div>

                <p class="text-xl md:text-2xl font-semibold whitespace-nowrap">
                    Cards: {{ pileCount }}
                </p>
            </div>

            <div v-if="pileCalledRank == 0 && turnOf == myPlayerNum">
                <Select
                    v-model="selectedRank"
                    :options="ranks"
                    optionLabel="name"
                    optionValue="code"
                    placeholder="Select rank"
                    class="w-40"
                />
            </div>
        </div>

        <!-- Right info -->
        <div class="flex-1 flex justify-end">
            <div class="w-56 min-h-24 flex flex-col justify-center">
                <p class="text-right text-xl md:text-2xl font-semibold">
                    Rank: {{ pileCalledRank == 0 ? 'No rank called' : pileCalledRank }}
                </p>
            </div>
        </div>
    </div>

    <div class="flex justify-center w-full">
        <!-- Call Lie -->
        <div class="flex-1 flex justify-center items-center">
            <Button
                label="Call lie"
                :severity="isLieButtonDisabled ? null : 'primary'"
                :disabled="isLieButtonDisabled"
                :class="[
                    isLieButtonDisabled
                        ? '!bg-gray-500 !border-gray-500 !text-white cursor-not-allowed'
                        : ''
                ]"
                @click="callALie()"
            />
        </div>

        <!-- Cards -->
        <div class="flex justify-center gap-2 flex-wrap">
            <div
                v-for="card in myCards"
                :key="card.id"
                :class="{ '-translate-y-2': mySelectedCards.includes(card.id) }"
                class="w-20 h-30 rounded-md transition-transform duration-300 ease-in-out hover:-translate-y-4 cursor-pointer overflow-hidden"
                @click="cardSelect(card)"
            >
                <Image
                    :src="getCardImage(card.id)"
                    :alt="card.name"
                    imageClass="w-20 h-30 rounded-md object-cover"
                />
            </div>

            <div v-if="myCards.length == 0" class="w-20 h-30 flex items-center justify-center text-center">
                You have no cards!
            </div>
        </div>

        <!-- Play Cards -->
        <div class="flex-1 flex justify-center items-center gap-2">
            <Button
                label="Play Cards"
                :severity="isCardButtonDisabled ? null : 'primary'"
                :disabled="isCardButtonDisabled"
                :class="[
                    isCardButtonDisabled
                        ? '!bg-gray-500 !border-gray-500 !text-white cursor-not-allowed'
                        : ''
                ]"
                @click="playSelectedCards()"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { authStore } from '../../store/auth';
import useGames from '../../composables/games';
import { useRoute } from 'vue-router';

const { playCards, callLie, isLoading } = useGames();
const route = useRoute();
const authUser = authStore();

const game = defineModel<any>('game');
const gameLoading = defineModel<any>('loading');

watch(gameLoading, (value) => {
    if (value !== undefined) isLoading.value = value;
}, { immediate: true });

const gameId = route.params.id;

const turnOf = computed(() => game.value.game_state.current_player_turn);
const turn = computed(() => game.value.game_state.turn);

const myPlayerNum = computed(() => game.value.game_state.players.indexOf(authUser.user.id) + 1);

const myCards = computed(() => {
    return game.value.game_state.player_decks['player' + myPlayerNum.value]['cards'];
});

const mySelectedCards = ref<number[]>([]);

const lastPlayerTurn = computed(() => game.value.game_state.last_player_turn);
const otherPlayerDecks = computed(() => game.value.game_state.player_decks);

const playersData = computed(() => {
    return game.value.playersInfo || game.value.players || [];
});

const getPlayerName = (index: number) => {
    const playerId = game.value.game_state.players[index];
    const playerData = playersData.value.find((p: any) => p.id === playerId);
    return playerData?.name || `Player ${index + 1}`;
};

const pileCount = computed(() => game.value.game_state.pile.count);
const pileCalledRank = computed(() => game.value.game_state.pile.called_rank);

const selectedRank = ref('');
const ranks = ref([
    { name: 'Ace', code: 'Ace' },
    { name: '2', code: '2' },
    { name: '3', code: '3' },
    { name: '4', code: '4' },
    { name: '5', code: '5' },
    { name: '6', code: '6' },
    { name: '7', code: '7' },
    { name: '8', code: '8' },
    { name: '9', code: '9' },
    { name: '10', code: '10' },
    { name: 'Jack', code: 'Jack' },
    { name: 'Queen', code: 'Queen' },
    { name: 'King', code: 'King' }
]);

const isRankNotSelected = computed(() => {
    return pileCalledRank.value == 0 && !selectedRank.value;
});

const isCardButtonDisabled = computed(() => {
    const notMyTurn = turnOf.value != myPlayerNum.value;
    const cardsNotSelected = mySelectedCards.value.length < 1;
    return isRankNotSelected.value || notMyTurn || isLoading.value || cardsNotSelected;
});

const isLieButtonDisabled = computed(() => {
    const lastPlayer = lastPlayerTurn.value == myPlayerNum.value;
    return pileCalledRank.value == 0 || isLoading.value || lastPlayer;
});

const getCardImage = (cardId: number) => {
    const extension = (cardId === 53 || cardId === 54) ? 'png' : 'svg';
    return `/images/Cards/${cardId}.${extension}`;
};

const cardSelect = (card: any) => {
    const index = mySelectedCards.value.indexOf(card.id);
    if (index > -1) {
        mySelectedCards.value.splice(index, 1);
    } else if (mySelectedCards.value.length < 4) {
        mySelectedCards.value.push(card.id);
    }
};

const playSelectedCards = () => {
    playCards(gameId, mySelectedCards.value, selectedRank.value);
    mySelectedCards.value = [];
    selectedRank.value = '';
};

const callALie = () => {
    mySelectedCards.value = [];
    selectedRank.value = '';
    callLie(gameId);
};
</script>