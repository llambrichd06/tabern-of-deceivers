<template>
    <section> <!--Game-->
        <!-- <GameComponent :gameState=""/> -->
    </section>
    <section> 
        <Chat/>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Chat from "../../../components/roomComponents/Chat.vue";
import GameComponent from './GameComponent.vue'
import { useRoute } from "vue-router";

const route = useRoute()
const gameId = route.params.id

onMounted(async () => {
    loading.value = true;
    try {
    } catch (error) {
    }finally {
        loading.value = false;
    }
});

</script>
