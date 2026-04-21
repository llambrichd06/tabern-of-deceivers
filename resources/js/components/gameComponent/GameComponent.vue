<template>
	<!--Tendrem el nom del jugador potser amb la imatge del perfil, despres la imatge de l'equena de la carta amb un numero que 
	        indica quantes cartes te i en cas de que podem posar un "xat" o text que digui cosses com:
	        ha robat x cartes, o ha tirat 3 reis-->
	        <div class="flex justify-center gap-4"><!--Up row--> 
	            <div v-for="(decks, player) in otherPlayerDecks" class="" :class="{'hidden': player === 'player'+myPlayerNum || decks.count < 1}">
					<div class="p-4 w-full flex flex-col items-center justify-center">
	                	<div  class="flex flex-col items-center">
							<div>
								<!-- Currently just the player number, we should probs get the player names in the game state -->
								{{ player }} 
							</div>
							<div class="w-20 h-30 bg-gray-400 border-white border rounded-md flex justify-end p-1">
								<p class="w-6 h-6 bg-gray-800 p-0 text-center">{{ decks.count }}</p>
							</div>
						</div>
					</div>
	            </div>
	        </div><!--End Up row-->

	        <div class="flex justify-center my-20 w-full gap-4"><!--Mid row-->
				
				<div class="flex-1 flex justify-end">
					<div class=" w-30 h-21 bg-gray-400 border-white border rounded-md flex flex-col justify-center items-center">
						<p class="text-center">Turn: {{ turn }}</p>
						<p v-if="myPlayerNum == turnOf" class="text-center">Your turn</p>
						<p v-else class="text-center">Player {{ turnOf }}'s turn</p>
						<p class="text-center">You are Player {{ myPlayerNum }}</p>
						
					</div>
				</div>

				<div class="w-20 h-30 bg-gray-400 border-white border rounded-md flex">
					<p class="text-center">Backwards card</p>
				</div>
				<div class="flex-1">
					<div class=" w-30 h-21 bg-gray-400 border-white border rounded-md flex flex-col justify-center items-center">
						<p class="text-center">Cards: {{ pileCount }}</p>
						<p class="text-center">Rank: {{ pileCalledRank == 0 ? 'No rank called' : pileCalledRank }}</p>
					</div>
				</div>
				<!--Aquesta tindra una foto de una carta (esquena d'ella) i tambe possara quin rang sesta jugant i quantes cartes s'han jugat la ultima vegada-->
	        </div><!--End Mid row-->
	
	
	        <div><!--Almost Bottom row-->
	            <!--Aquesta tindra els altres dos jugadors (en cas de 5), un jugador estara a l'esquerra de tot i l'altre a la dreta del tot-->
	            <div></div>
	            <div></div>
	        </div><!--End of almost Bottom row-->
			<div class="flex justify-center w-full">
				<div class="flex-1 flex justify-center items-center">
					<Button
						label="Call lie"
						severity="primary"
						:disabled="isLieButtonDisabled"
						@click="callALie()"
					/>
				</div>
	        	<div class="flex justify-center"><!--Bottom row-->
						<!-- Ready this to play cards -->
					<div v-for="card in myCards" :key="card.id" :class="{'-translate-y-2': mySelectedCards.includes(card.id)}" class="w-20 h-30 bg-gray-400 border-white border rounded-md transition-transform duration-300 ease-in-out 
            	hover:-translate-y-4 cursor-pointer" @click="cardSelect(card)"> 
						{{ card.name }}
					</div>
					<div v-if="myCards.length == 0" class="w-20 h-30">
						You have no cards!
					</div>
	        	    <!--Cartes del usuari-->
	        	</div>

				<div class="flex-1 flex justify-center items-center gap-2">
					<Button
						label="Play Cards"
						severity="primary"
						:disabled="isCardButtonDisabled"
						@click="playSelectedCards()"
					/>
					<div v-if="pileCalledRank == 0 && turnOf == myPlayerNum">
						<Select v-model="selectedRank" :options="ranks" optionLabel="name" optionValue="code" placeholder="Select a rank" />
					</div>
				</div>
				
			</div><!--End of Bottom row-->
</template>

<script setup lang="ts">
	import { computed, ref, watch } from 'vue';
	import { authStore } from '../../store/auth';
	import useGames from '../../composables/games';
	import { useRoute } from 'vue-router';

	const { playCards, callLie, isLoading } = useGames()
	const route = useRoute()
	const authUser = authStore()

	/**
	 * General game data
	 */
	const game = defineModel<any>('game');
	const gameLoading = defineModel<any>('loading'); 

	watch(gameLoading, (value) => { //This "links" both loading states on the component
		if (value !== undefined) isLoading.value = value;
	}, { immediate: true })

	const gameId = route.params.id;
	console.log(game.value.game_state.players)
	const turnOf = computed(() => game.value.game_state.current_player_turn);
	const turn = computed(() => game.value.game_state.turn)
	/**
	 * Logged player data
	 */
	const myPlayerNum = computed(() => game.value.game_state.players.indexOf(authUser.user.id) + 1);
	const myCards = computed(() => {
  		return game.value.game_state.player_decks['player'+myPlayerNum.value]['cards']
	});
	// const myCards = toRef(game.value.game_state, 'player_decks');
	const mySelectedCards = ref([])
	/**
	 * Other player data
	 */
	const lastPlayerTurn = computed(() =>  game.value.game_state.last_player_turn)
	const otherPlayerDecks = computed(() =>  game.value.game_state.player_decks)

	/**
	 * Pile data
	 */
	const pileCount = computed(() => game.value.game_state.pile.count);
	const pileCalledRank = computed(() => game.value.game_state.pile.called_rank);
	const pileLastPlayedCardsCount = computed(() => game.value.game_state.pile.last_played_cards_count);

	/**
	 * Called rank select
	 */
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
	const isCardButtonDisabled = computed(() => {
		let notMyTurn = turnOf.value != myPlayerNum.value;
		let cardsNotSelected = mySelectedCards.value.length < 1;
		
  		return isRankNotSelected.value || notMyTurn || isLoading.value || cardsNotSelected;
	});
	const isLieButtonDisabled = computed(() => {
		let lastPlayer = lastPlayerTurn.value == myPlayerNum.value;
  		return pileCalledRank.value == 0 || isLoading.value || lastPlayer;
	});
	const isRankNotSelected = computed(() => {
		return pileCalledRank.value == 0 && !selectedRank.value
	})


	const cardSelect = (card) => {
		const index = mySelectedCards.value.indexOf(card.id);
    	if (index > -1) {
    	    mySelectedCards.value.splice(index, 1);
    	} else if (mySelectedCards.value.length < 4) {
    	    mySelectedCards.value.push(card.id);
    	}
	}

	const playSelectedCards = () => {
		playCards(gameId, mySelectedCards.value, selectedRank.value)
		mySelectedCards.value = []
		selectedRank.value = '';
	}

	const callALie = () => {
		mySelectedCards.value = []
		selectedRank.value = '';
		callLie(gameId)
	}

	/**
	 * USER GAME STATE STRUCTURE:
	 * gamestate: {
	 * 	pile: {
	 * 		'count' => 0,
	 * 		'last_played_cards_count' => 0,
     *      'cards' => [],
     *      'last_played_cards' => [],
     *      'called_rank' => '0',
	 * 	},
	 * 	player_decks: { (player number based on their position in the array minus one)
     *      'player1' => {'count' => 0, 'cards' => {}},
     *      'player2' => {'count' => 0, 'cards' => {}},
     *      'player3' => {'count' => 0, 'cards' => {}},
     *      'player4' => {'count' => 0, 'cards' => {}},
     *      'player5' => {'count' => 0, 'cards' => {}},
     *      'player6' => {'count' => 0, 'cards' => {}},
     *  },
	 * 	players = [],
	 *  current_player_turn = 0,
	 *  last_player_turn = 0,
	 *  turn = 0,
	 * }
	 */

</script>
