<template>
    <section>
        <h1 class="text-4xl font-bold text-surface-900 dark:text-surface-0 mb-4">Tabern of Deceivers</h1>
        <p class="text-xl text-surface-600 dark:text-surface-400 mb-8">Lie, decieve and scheme! In the tavern of deceivers the best liar is the winner!</p>
        <Button label="Play now" as="router-link" to="rooms" />
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
            <div v-if="rankings != 0">
                    <div v-if="loading">Loading...</div>
        
                    <ul v-else>
                        <li 
                            v-for="(lb, index) in bestUsers.leaderboards" 
                            :key="lb.id"
                        >
                            #{{ index + 1 }} <br>
                            <strong>{{ lb.user.name }}</strong> <br>
                            {{ lb.points }} pts <br>
                            (W: {{ lb.wins }} / L: {{ lb.losses }})
                            <br><br>
                        </li>
                    
                        <li v-if="rankings === 2">
                            #3 <br>
                            <strong>No one :v</strong> <br>
                            no pts <br>
                            (W: none / L: none)
                            <br><br>
                        </li>
                        <li v-else-if="rankings === 1">
                            #2 <br>
                            <strong>No one :v</strong> <br>
                            no pts <br>
                            (W: none / L: none)
                            <br><br>

                            #3 <br>
                            <strong>No one :v</strong> <br>
                            no pts <br>
                            (W: none / L: none)
                            <br><br>
                        </li>
                    </ul>
            </div>
            <div v-else>
                <p><b>No ranking found</b></p>
            </div>
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
import { ref, onMounted } from "vue";
import useLeaderboards from "../../../composables/leaderboards";

const { getBestUsers } = useLeaderboards();
const loading = ref(false);
const rankings = ref(true);

const bestUsers = ref({ leaderboards: [] });

onMounted(async () => {
    loading.value = true;

    try {
        const response = await getBestUsers();
        
        bestUsers.value = response.data;
        rankings.value = bestUsers.value.leaderboards.length;
    } catch (error) {
        console.error("Failed to load leaderboard:", error);
    }finally {
        loading.value = false;
    }
});

</script>