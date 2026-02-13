<template>
    <section>
        <h1 class="text-4xl font-bold text-surface-900 dark:text-surface-0 mb-4">Tabern of Deceivers</h1>
        <p class="text-xl text-surface-600 dark:text-surface-400 mb-8">Lie, decieve and scheme! In the tavern of deceivers the best liar is the winner!</p>
        <button>Play Now</button>
    </section>
    <section>
        <h2>How does the game work?</h2>
        <p>This is a game about lying to others! To be able to win you will need to lie, decive and predict the other’s moves while trying to keep your friendships intact.</p>
        <p>Some features you will discover about the game:</p>
        <ul>
            <li>2-6 player matches</li>
            <li>Easy to learn, hard to master</li>
            <li>A mix of luck and strategy</li>
        </ul>
        <h3><bold>Read some more detailed rules here:</bold></h3>
        <button>How to play</button>
    </section>
    <section>
        <div>
            <h2>Best Players</h2>
            <div>best 3</div> <!--Trucar al top 3 de leaderboard-->
            <div>best 1</div>
            <div>best 3</div>
        </div>
        <p>See the ranking of other players around the world! There, you can see your own ranking compared to others. Try to aim for the top!</p>
        <button>View Rankings</button>
    </section>
    
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">        
        <div class="flex gap-4">
            <template v-if="!authStore().user?.name">
                <Button label="Iniciar Sesión" as="router-link" to="/login" size="large" />
                <Button label="Registrarse" as="router-link" to="/register" severity="secondary" size="large" />
            </template>
            <template v-else>
                <Button label="Ir al Dashboard" as="router-link" to="/app" size="large" />
            </template>
        </div>
    </div>
</template>

<script setup>
import { authStore } from "@/store/auth";
</script>
