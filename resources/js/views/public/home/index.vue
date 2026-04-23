<template>
    <div class="page-content min-h-screen bg-[#520B93] text-white">
        <!-- HERO (UNCHANGED) -->
        <section class="relative overflow-hidden min-h-70 md:min-h-80">
            <div class="absolute inset-0 bg-emerald-900"></div>
            
            <div
                class="absolute inset-0 bg-center bg-cover bg-no-repeat"
                style="background-image: url('/images/tabern-background.gif');"
            ></div>

            <div
                class="absolute left-0 right-0 bottom-0 h-[42%] z-10
                    bg-linear-to-t from-black/75 via-black/45 to-transparent
                    md:inset-y-0 md:left-0 md:right-auto md:h-auto md:w-[42%]
                    md:bg-linear-to-r md:from-black/70 md:via-black/45 md:to-transparent">
            </div>

            <div class="relative z-20 flex min-h-70 md:min-h-80 items-center">
                <div class="mx-auto w-full max-w-5xl px-4">
                    <div class="grid grid-cols-1 md:grid-cols-[240px_1fr] pt-20 pb-8 md:pt-8">

                        <div class="order-1 md:order-2 flex flex-col items-center justify-center text-center">
                            <h1 class="text-white text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight mb-6">
                                Tabern of Deceivers
                            </h1>

                            <Button
                                label="PLAY NOW"
                                as="router-link"
                                to="/rooms"
                                severity="primary"
                                class="border-0! px-10! py-4! text-2xl! md:px-12! md:text-3xl! font-bold!"
                            />
                        </div>

                        <div class="order-2 md:order-1 mt-8 md:mt-0 flex flex-col justify-center items-center md:items-start text-center md:text-left text-white max-w-full md:max-w-55">
                            <h3 class="font-semibold leading-tight mb-4 text-3xl md:text-4xl">
                                Lie, decieve and scheme!
                            </h3>
                            <p class="text-base md:text-lg leading-relaxed text-white/95">
                                In the tavern of deceivers the best liar is the winner!
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section class="mt-10 rounded-xl px-6 py-8 md:px-10 md:py-10">
            <div class="mx-auto max-w-5xl">
                <h2 class="text-4xl text-center font-bold md:text-6xl">
                    How does the game work?
                </h2>
                
                <p class="mx-auto mb-8 max-w-3xl text-center leading-8">
                    This is a game about lying to others! To be able to win you will need to lie,
                    decive and predict the other’s moves while trying to keep your friendships intact.
                </p>
                
                <div class="grid gap-6 md:grid-cols-3 mt-6">

                    <div class="rounded-3xl p-8 bg-purple-300/35 min-h-50 flex flex-col items-center text-center shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
                        <i class="pi pi-users text-4xl mb-4 text-white/90"></i>
                        <p class="font-semibold text-xl mb-2">2–6 Players</p>
                        <p class="text-base opacity-90 leading-relaxed">
                            Play with friends or challenge others in small group matches full of deception.
                        </p>
                    </div>

                    <div class="rounded-3xl p-8 bg-purple-300/35 min-h-50 flex flex-col items-center text-center shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
                        <i class="pi pi-star text-4xl mb-4 text-white/90"></i>
                        <p class="font-semibold text-xl mb-2">Easy to Learn, Hard to Master</p>
                        <p class="text-base opacity-90 leading-relaxed">
                            Simple rules make it easy to start, but mastering the game takes skill.
                        </p>
                    </div>

                    <div class="rounded-3xl p-8 bg-purple-300/35 min-h-50 flex flex-col items-center text-center shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
                        <i class="pi pi-sitemap text-4xl mb-4 text-white/90"></i>
                        <p class="font-semibold text-xl mb-2">Strategy & Luck</p>
                        <p class="text-base opacity-90 leading-relaxed">
                            Combine clever thinking with a bit of luck to outplay your opponents.
                        </p>
                    </div>

                </div>

                <div class="mt-10 flex justify-center">
                    <Button
                        label="How To Play"
                        as="router-link"
                        to="/rules"
                        severity="secondary"
                        class="rounded-2xl! px-16! py-4! text-xl! font-semibold! shadow-[0_15px_20px_rgba(0,0,0,0.3)]"
                    />
                </div>
            </div>
        </section>

        <!-- BEST PLAYERS -->
        <section class="mt-12 px-4">
            <div class="mx-auto max-w-5xl rounded-2xl bg-purple-300/35 px-6 pt-8 pb-0 md:px-10 md:pt-10 shadow-[0_15px_20px_rgba(0,0,0,0.3)]">
                <h2 class="mb-8 text-center text-4xl font-bold md:text-6xl">
                    Rankings
                </h2>
                
                <p class="mx-auto mb-8 max-w-3xl text-center leading-8">
                    See the ranking of other players around the world! There, you can see your own ranking
                    compared to others. Try to aim for the top!
                </p>

                <div class="mb-12 flex justify-center w-full">
                    <Button
                        label="View Rankings"
                        as="router-link"
                        to="/leaderboard"
                        severity="secondary"
                        class="rounded-2xl! px-16! py-4! text-xl! font-semibold! shadow-[0_15px_20px_rgba(0,0,0,0.3)]"
                    />
                </div>

                <div v-if="hasRankings">
                    <div v-if="loading" class="text-center">
                        Loading...
                    </div>

                    <div v-else class="flex items-end justify-center gap-4 md:gap-10 text-black">
                        <!-- 2nd -->
                        <div v-if="bestUsers.leaderboards?.length >= 2" class="flex flex-col items-center justify-end">
                            <div class="h-20 w-20 rounded-full border border-black bg-surface-200"></div>

                            <div class="mt-2 flex min-h-[150px] w-24 flex-col rounded-t-xl bg-slate-200 px-3 pt-3 pb-3 text-center md:w-28">
                                <p class="font-bold break-words">
                                    {{ bestUsers.leaderboards[1]?.user?.name ?? "No one :v" }}
                                </p>
                                <p class="mt-2">Wins: {{ bestUsers.leaderboards[1]?.wins ?? "none" }}</p>
                                <p>Points: {{ bestUsers.leaderboards[1]?.points ?? "no pts" }}</p>
                            </div>
                        </div>

                        <!-- 1st -->
                        <div v-if="bestUsers.leaderboards?.length >= 1" class="flex flex-col items-center justify-end">
                            <div class="relative flex items-center justify-center">
                                <img 
                                    src="/images/crown.svg"
                                    class="absolute -top-7 left-22/30 -translate-x-1/2 w-10 h-10 rotate-22"
                                />
                                <div class="h-20 w-20 rounded-full border border-black bg-surface-200"></div>
                            </div>

                            <div class="mt-2 flex min-h-[190px] w-24 flex-col rounded-t-xl bg-yellow-300 px-3 pt-3 pb-3 text-center md:w-28">
                                <p class="font-bold break-words">
                                    {{ bestUsers.leaderboards[0]?.user?.name ?? "No one :v" }}
                                </p>
                                <p class="mt-2">Wins: {{ bestUsers.leaderboards[0]?.wins ?? "none" }}</p>
                                <p>Points: {{ bestUsers.leaderboards[0]?.points ?? "no pts" }}</p>
                            </div>
                        </div>

                        <!-- 3rd -->
                        <div v-if="bestUsers.leaderboards?.length >= 3" class="flex flex-col items-center justify-end">
                            <div class="h-20 w-20 rounded-full border border-black bg-surface-200"></div>

                            <div class="mt-2 flex min-h-[120px] w-24 flex-col rounded-t-xl bg-orange-500 px-3 pt-3 pb-3 text-center md:w-28">
                                <p class="font-bold break-words">
                                    {{ bestUsers.leaderboards[2]?.user?.name ?? "No one :v" }}
                                </p>
                                <p class="mt-2">Wins: {{ bestUsers.leaderboards[2]?.wins ?? "none" }}</p>
                                <p>Points: {{ bestUsers.leaderboards[2]?.points ?? "no pts" }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center">
                    <p><b>No ranking found</b></p>
                </div>
            </div>
        </section>

        <!-- CTA (UNCHANGED) -->
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
            <div class="flex gap-4">
                <template v-if="!auth.user?.name">
                    <Button label="Iniciar Sesión" as="router-link" to="/login" size="large" />
                    <Button label="Registrarse" as="router-link" to="/register" severity="secondary" size="large" />
                </template>
                <template v-else>
                    <Button 
                        label="Ir al Dashboard" 
                        as="router-link" 
                        to="/app" 
                        size="large" 
                        severity="secondary"
                    />
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import { authStore } from "@/store/auth";
import { ref, computed, onMounted } from "vue";
import useLeaderboards from "../../../composables/leaderboards";

const auth = authStore();
const { getBestUsers } = useLeaderboards();

const loading = ref(false);
const bestUsers = ref({ leaderboards: [] });

const hasRankings = computed(() => bestUsers.value.leaderboards.length > 0);

onMounted(async () => {
    loading.value = true;

    try {
        const response = await getBestUsers();
        bestUsers.value = response.data;
    } catch (error) {
        console.error("Failed to load leaderboard:", error);
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
.page-content :deep(p) {
    font-size: 1rem;
    line-height: 1.75rem;
}

@media (min-width: 768px) {
    .page-content :deep(p) {
        font-size: 1.125rem;
        line-height: 1.75rem;
    }
}
</style>