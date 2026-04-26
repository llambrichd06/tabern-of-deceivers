<template>
    <div class="flex h-full min-h-0 flex-col justify-between gap-3 lg:gap-4">
        <!-- TOP PLAYERS -->
        <div class="flex flex-wrap justify-center gap-2 md:gap-3 lg:gap-4">
            <div
                v-for="(decks, player, index) in otherPlayerDecks"
                :key="player"
                :class="{ 'hidden': player === 'player' + myPlayerNum || (decks.count < 1 && player != 'player'+lastPlayerTurn) }"
                class="min-w-27.5"
            >
                <div class="flex w-full flex-col items-center justify-center rounded-2xl bg-white/8 px-2 py-3">
                    <div class="flex flex-col items-center gap-2">
                        <div class="text-center text-sm font-semibold leading-tight md:text-base">
                            {{ getPlayerNameFromNum(player) ?? getPlayerName(index) }}
                        </div>

                        <div class="flex flex-col items-center">
                            <Image
                                src="/images/Cards/backCard.png"
                                alt="Back of the Card"
                                imageClass="w-12 h-18 md:w-14 md:h-20 rounded-md object-cover"
                            />
                            <p class="mt-1 text-center text-xs font-medium md:text-sm">
                                Cards: {{ decks.count }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MIDDLE AREA -->
        <div class="relative min-h-[260px] lg:min-h-52.5">
            <div class="flex h-full items-center justify-center">
                <div class="flex items-center justify-center gap-4 lg:block">
                    <!-- PILE -->
                    <div class="flex flex-col items-center justify-center gap-1.5">
                        <div class="relative flex h-28 w-20 items-center justify-center md:h-32 md:w-24">
                            <!-- No cards -->
                            <div
                                v-if="pileCount === 0"
                                class="flex h-20 w-14 items-center justify-center rounded-md border-2 border-dashed border-white/60 md:h-24 md:w-16"
                            >
                                <span class="text-xs font-semibold text-white/70 md:text-sm">PILE</span>
                            </div>

                            <!-- One card -->
                            <div v-else-if="pileCount === 1" class="h-20 w-14 md:h-24 md:w-16">
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
                                    class="absolute left-1/2 top-1/2 h-20 w-14 md:h-24 md:w-16"
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

                        <p class="whitespace-nowrap text-base font-semibold md:text-lg">
                            Cards: {{ pileCount }}
                        </p>

                        <div v-if="pileCalledRank == 0 && turnOf == myPlayerNum" class="mt-1">
                            <Select
                                v-model="selectedRank"
                                :options="ranks"
                                optionLabel="name"
                                optionValue="code"
                                placeholder="Select rank"
                                class="w-32 md:w-40"
                            />
                        </div>
                    </div>

                    <!-- INFO MOBILE -->
                    <div class="flex lg:hidden">
                        <div class="w-32 rounded-2xl bg-white/8 px-3 py-3 text-center">
                            <p class="text-xs font-semibold">
                                Turn: {{ turn }}
                            </p>
                            <p
                                v-if="myPlayerNum == turnOf"
                                class="text-sm font-semibold"
                            >
                                Your turn
                            </p>
                            <p
                                v-else
                                class="text-sm font-semibold"
                            >
                                Turn of {{ getPlayerNameFromNum('player'+turnOf ?? 'player1')}}
                            </p>
                            <p class="text-sm font-semibold">
                                Rank: {{ pileCalledRank == 0 ? 'None' : pileCalledRank }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFO DESKTOP -->
            <div class="hidden lg:absolute lg:right-0 lg:top-1/2 lg:flex lg:-translate-y-1/2 lg:justify-end">
                <div class="w-full max-w-52.5 rounded-2xl bg-white/8 px-4 py-3">
                    <p class="text-center text-sm font-semibold md:text-base lg:text-right">
                        Turn: {{ turn }}
                    </p>
                    <p
                        v-if="myPlayerNum == turnOf"
                        class="text-center text-base font-semibold md:text-lg lg:text-right"
                    >
                        Your turn
                    </p>
                    <p
                        v-else
                        class="text-center text-base font-semibold md:text-lg lg:text-right"
                    >
                        Turn of {{ getPlayerNameFromNum('player'+turnOf)}}
                    </p>
                    <p class="text-center text-base font-semibold md:text-lg lg:text-right">
                        Rank: {{ pileCalledRank == 0 ? 'No rank called' : pileCalledRank }}
                    </p>
                </div>
            </div>
        </div>

        <!-- BOTTOM ACTIONS -->
        <div class="mt-4 grid grid-cols-1 items-center gap-6 lg:mt-0 lg:grid-cols-[140px_minmax(0,1fr)_140px] lg:gap-4">
            <!-- CALL LIE -->
            <div class="order-2 mt-2 flex justify-center lg:order-1 lg:mt-0 lg:justify-start">
                <Button
                    label="Call lie"
                    :severity="isLieButtonDisabled ? null : 'primary'"
                    :disabled="isLieButtonDisabled"
                    :class="[
                        'w-full sm:w-auto',
                        isLieButtonDisabled
                            ? '!bg-gray-500 !border-gray-500 !text-white cursor-not-allowed'
                            : ''
                    ]"
                    @click="callALie()"
                />
            </div>

            <!-- MY CARDS -->
            <div class="order-1 mb-2 flex flex-wrap justify-center gap-2 md:gap-2 lg:order-2">
                <div
                    v-for="card in myCards"
                    :key="card.id"
                    :class="getCardClass(card.id)"
                    class="playing-card h-18 w-12 cursor-pointer overflow-hidden rounded-md border border-transparent transition-all duration-200 ease-out md:h-20 md:w-14"
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
                    class="w-full py-4 text-center text-sm md:text-base"
                >
                    You have no cards!
                </div>
            </div>

            <!-- PLAY CARDS -->
            <div class="order-3 mt-2 flex justify-center lg:mt-0 lg:justify-end">
                <Button
                    label="Play Cards"
                    :severity="isCardButtonDisabled ? null : 'primary'"
                    :disabled="isCardButtonDisabled"
                    :class="[
                        'w-full sm:w-auto',
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

const getPlayerNameFromNum = (playerNum) => {
    if (!playerNum) {
        return 'Player'
    }
    return otherPlayerDecks?.value[playerNum]['user_name']
}

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

const isCardSelected = (cardId: number) => {
    return mySelectedCards.value.includes(cardId);
};

const getCardClass = (cardId: number) => {
    const selected = isCardSelected(cardId);

    return {
        'card-selected': selected,
        'card-unselected': !selected
    };
};

const cardSelect = (card: any) => {
    const index = mySelectedCards.value.indexOf(card.id);
    if (index > -1) {
        mySelectedCards.value.splice(index, 1);
    } else if (mySelectedCards.value.length < 6) {
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

<style scoped>
.playing-card {
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.22);
}

.card-unselected:hover {
    transform: translateY(-3px);
    border-color: rgba(0, 0, 0, 0.9);
    box-shadow: 0 9px 18px rgba(0, 0, 0, 0.26);
}

.card-selected {
    transform: translateY(-10px);
    border-color: transparent;
    box-shadow: 0 12px 22px rgba(0, 0, 0, 0.32);
}

.card-selected:hover {
    transform: translateY(-10px);
    border-color: transparent;
    box-shadow: 0 13px 23px rgba(0, 0, 0, 0.34);
}
</style>