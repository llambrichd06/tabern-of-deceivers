<template>
	<!--Tendrem el nom del jugador potser amb la imatge del perfil, despres la imatge de l'equena de la carta amb un numero que 
	        indica quantes cartes te i en cas de que podem posar un "xat" o text que digui cosses com:
	        ha robat x cartes, o ha tirat 3 reis-->
	        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"><!--Up row--> 
	            <!--Aqui estaran els jugadors 1-3 que no siguix tu-->
	            <!--foreach players as player-->
	            <div v-for="item in items" class="bg-gray-200 p-4">
	                {{ item }}
	            </div>
	        </div><!--End Up row-->
	
	
	        <div><!--Mid row-->
	            <div></div><!--Aquesta tindra una foto de una carta (esquena d'ella) i tambe possara quin rang sesta jugant i quantes cartes s'han jugat la ultima vegada-->
	            <div></div><!--Aquesta tindra la foto de l'equena de la carta i posara el numero total de cartes que hi ha a la pila-->
	        </div><!--End Mid row-->
	
	
	        <div><!--Almost Bottom row-->
	            <!--Aquesta tindra els altres dos jugadors (en cas de 5), un jugador estara a l'esquerra de tot i l'altre a la dreta del tot-->
	            <div></div>
	            <div></div>
	        </div><!--End of almost Bottom row-->
	        <div><!--Bottom row-->
	            <!--Cartes del usuari-->
	        </div><!--End of Bottom row-->
</template>

<script setup lang="ts">
defineProps<{
	items: any;
}>()
</script>
