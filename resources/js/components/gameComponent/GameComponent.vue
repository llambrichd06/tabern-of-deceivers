<template>
    <div class="flex h-full min-h-0 flex-col justify-between gap-4 lg:gap-5">
        <!-- TOP PLAYERS -->
        <div class="flex justify-center gap-2 md:gap-3 lg:gap-4 flex-wrap">
            <div
                v-for="(decks, player, index) in otherPlayerDecks"
                :key="player"
                :class="{ 'hidden': player === 'player' + myPlayerNum || decks.count < 1 || turnOf == lastPlayerTurn }"
                class="min-w-[110px]"
            >
                <div class="flex w-full flex-col items-center justify-center rounded-2xl bg-white/8 px-2 py-3">
                    <div class="flex flex-col items-center gap-2">
                        <div class="text-center text-sm md:text-base font-semibold leading-tight">
                            {{ getPlayerName(index) }}
                        </div>

                        <div class="flex flex-col items-center">
                            <Image
                                src="/images/Cards/backCard.png"
                                alt="Back of the Card"
                                imageClass="w-12 h-18 md:w-14 md:h-20 rounded-md object-cover"
                            />
                            <p class="mt-1 text-center text-xs md:text-sm font-medium">
                                Cards: {{ decks.count }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CENTER AREA -->
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto_1fr] items-center gap-4 lg:gap-6">
            <!-- LEFT INFO -->
            <div class="flex justify-center lg:justify-start order-2 lg:order-1">
                <div class="w-full max-w-[260px] rounded-2xl bg-white/8 px-4 py-3">
                    <p class="text-center lg:text-left text-base md:text-lg font-semibold">
                        Turn: {{ turn }}
                    </p>
                    <p
                        v-if="myPlayerNum == turnOf"
                        class="text-center lg:text-left text-base md:text-lg font-semibold"
                    >
                        Your turn
                    </p>
                    <p
                        v-else
                        class="text-center lg:text-left text-base md:text-lg font-semibold"
                    >
                        Player {{ turnOf }}'s turn
                    </p>
                    <p class="text-center lg:text-left text-base md:text-lg font-semibold">
                        You are Player {{ myPlayerNum }}
                    </p>
                </div>
            </div>

            <!-- MIDDLE PILE -->
            <div class="flex flex-col items-center justify-center gap-3 order-1 lg:order-2">
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="relative w-24 h-32 md:w-28 md:h-36 flex items-center justify-center">
                        <!-- No cards -->
                        <div
                            v-if="pileCount === 0"
                            class="w-16 h-24 md:w-20 md:h-28 border-2 border-dashed border-white/60 rounded-md flex items-center justify-center"
                        >
                            <span class="text-white/70 text-xs md:text-sm font-semibold">PILE</span>
                        </div>

                        <!-- One card -->
                        <div v-else-if="pileCount === 1" class="w-16 h-24 md:w-20 md:h-28">
                            <Image
                                src="/images/Cards/backCard.png"
                                alt="Back of the Card"
                                imageClass="w-full h-full rounded-md object-cover shadow-lg border border-black"
                            />
                        </div>

                        <!-- Multiple cards -->
                        <div v-else class="absolute inset-0">
                            <div
                                v-for="(_, index) in visiblePileCards"
                                :key="index"
                                class="absolute left-1/2 top-1/2 w-16 h-24 md:w-20 md:h-28"
                                :style="getPileCardStyle(index, visiblePileCards)"
                            >
                                <Image
                                    src="/images/Cards/backCard.png"
                                    alt="Back of the Card"
                                    imageClass="w-full h-full rounded-md object-cover shadow-lg border border-black"
                                />
                            </div>
                        </div>
                    </div>

                    <p class="text-base md:text-lg font-semibold whitespace-nowrap">
                        Cards: {{ pileCount }}
                    </p>

                    <p class="text-sm md:text-base font-semibold text-center">
                        Rank: {{ pileCalledRank == 0 ? 'No rank called' : pileCalledRank }}
                    </p>
                </div>

                <div v-if="pileCalledRank == 0 && turnOf == myPlayerNum">
                    <Select
                        v-model="selectedRank"
                        :options="ranks"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Select rank"
                        class="w-36 md:w-40"
                    />
                </div>
            </div>

            <!-- RIGHT INFO -->
            <div class="flex justify-center lg:justify-end order-3">
                <div class="w-full max-w-[260px] rounded-2xl bg-white/8 px-4 py-3">
                    <p class="text-center lg:text-right text-base md:text-lg font-semibold">
                        Selected: {{ mySelectedCards.length }}/4
                    </p>
                    <p class="text-center lg:text-right text-base md:text-lg font-semibold">
                        Hand: {{ myCards.length }} cards
                    </p>
                </div>
            </div>
        </div>

        <!-- BOTTOM ACTIONS -->
        <div class="grid grid-cols-1 lg:grid-cols-[140px_minmax(0,1fr)_140px] items-center gap-3 lg:gap-4">
            <!-- CALL LIE -->
            <div class="flex justify-center lg:justify-start order-2 lg:order-1">
                <Button
                    label="Call lie"
                    :severity="isLieButtonDisabled ? null : 'primary'"
                    :disabled="isLieButtonDisabled"
                    :class="[
                        'w-full lg:w-auto',
                        isLieButtonDisabled
                            ? '!bg-gray-500 !border-gray-500 !text-white cursor-not-allowed'
                            : ''
                    ]"
                    @click="callALie()"
                />
            </div>

            <!-- MY CARDS -->
            <div class="flex justify-center gap-1.5 md:gap-2 flex-wrap order-1 lg:order-2">
                <div
                    v-for="card in myCards"
                    :key="card.id"
                    :class="{ '-translate-y-2': mySelectedCards.includes(card.id) }"
                    class="w-12 h-18 md:w-14 md:h-20 rounded-md transition-transform duration-300 ease-in-out hover:-translate-y-3 cursor-pointer overflow-hidden"
                    @click="cardSelect(card)"
                >
                    <Image
                        :src="getCardImage(card.id)"
                        :alt="card.name"
                        imageClass="w-full h-full rounded-md object-cover"
                    />
                </div>

                <div
                    v-if="myCards.length == 0"
                    class="w-full text-center text-sm md:text-base py-4"
                >
                    You have no cards!
                </div>
            </div>

            <!-- PLAY CARDS -->
            <div class="flex justify-center lg:justify-end order-3">
                <Button
                    label="Play Cards"
                    :severity="isCardButtonDisabled ? null : 'primary'"
                    :disabled="isCardButtonDisabled"
                    :class="[
                        'w-full lg:w-auto',
                        isCardButtonDisabled
                            ? '!bg-gray-500 !border-gray-500 !text-white cursor-not-allowed'
                            : ''
                    ]"
                    @click="playSelectedCards()"
                />
            </div>
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

const visiblePileCards = computed(() => {
    return Math.min(pileCount.value || 0, 5);
});

const getPileCardStyle = (index: number, total: number) => {
    const spreadsByTotal: Record<number, number[]> = {
        2: [-6, 6],
        3: [-10, 0, 10],
        4: [-12, -4, 4, 12],
        5: [-14, -7, 0, 7, 14]
    };

    const offsetsByTotal: Record<number, number[]> = {
        2: [-4, 4],
        3: [-8, 0, 8],
        4: [-10, -3, 3, 10],
        5: [-12, -6, 0, 6, 12]
    };

    const rotations = spreadsByTotal[total] || [0];
    const offsets = offsetsByTotal[total] || [0];

    const rotation = rotations[index] ?? 0;
    const translateX = offsets[index] ?? 0;

    return {
        transform: `translate(-50%, -50%) translateX(${translateX}px) rotate(${rotation}deg)`,
        zIndex: index + 1
    };
};

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