<template>
    <div class="page-content min-h-screen bg-[#520B93] text-white">
    <!-- Banner -->
        <section class="relative overflow-hidden min-h-70 md:min-h-80">
            <!-- Green fallback / base background -->
            <div class="absolute inset-0 bg-emerald-900"></div>

            <!-- Tavern background image -->
            <div
                class="absolute inset-0 bg-center bg-cover bg-no-repeat"
                style="background-image: url('/images/Banner.webp');"
            ></div>
            
            <div class="absolute inset-0 bg-black/35"></div>

            <!-- Centered content -->
            <div class="relative z-20 flex min-h-70 md:min-h-80 items-center justify-center">
                <div class="mx-auto w-full max-w-5xl px-4">
                    <div class="flex flex-col items-center justify-center text-center pt-20 pb-8 md:pt-8">

                        <h1 class="mb-4 text-4xl font-extrabold tracking-tight text-[#F8FAFC] drop-shadow-[0_4px_12px_rgba(0,0,0,0.85)] sm:text-5xl md:text-7xl">
                            Tabern of Deceivers
                        </h1>

                        <h3 class="mb-3 text-3xl font-semibold leading-tight text-[#E5E7EB] drop-shadow-[0_3px_10px_rgba(0,0,0,0.8)] md:text-4xl">
                            Lie, decieve and scheme!
                        </h3>

                        <p class="mb-8 max-w-2xl text-base leading-relaxed text-[#F1F5F9] drop-shadow-[0_2px_8px_rgba(0,0,0,0.8)] md:text-lg">
                            In the tavern of deceivers the best liar is the winner!
                        </p>

                        <Button
                            label="PLAY NOW"
                            as="router-link"
                            to="/rooms"
                            severity="primary"
                            class="border-0! px-10! py-4! text-2xl! font-bold! md:px-12! md:text-3xl!"
                        />

                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section class="mt-10 rounded-xl px-6 py-8 md:px-10 md:py-10">
            <div class="mx-auto max-w-5xl">
                <h2 class="mb-6 text-4xl text-center font-bold md:text-6xl">
                    How does the game work?
                </h2>
                
                <p class="mx-auto mb-6 max-w-3xl text-center leading-8">
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

                <div class="mt-6 flex justify-center">
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
        <section class="mt-16 px-4 pb-14 md:pb-20">
            <div class="relative mx-auto flex min-h-140 max-w-5xl flex-col rounded-2xl bg-purple-300/35 px-6 pt-8 pb-0 shadow-[0_15px_20px_rgba(0,0,0,0.3)] md:min-h-155 md:px-10 md:pt-10">
    
                <img src="/images/confetti_chunk_1.svg" class="absolute bottom-4/9 left-20 hidden w-9 -rotate-120 md:block z-0 opacity-80" alt="" aria-hidden="true" />
                <img src="/images/confetti_chunk_1.svg" class="absolute bottom-20 right-30 hidden w-5 rotate-150 md:block z-0 opacity-80" alt="" aria-hidden="true" />
                <img src="/images/confetti_chunk_3.svg" class="absolute bottom-32 left-20 hidden w-14 -rotate-30 md:block z-0" alt="" aria-hidden="true" />
                    
                <img src="/images/confetti_chunk_2.svg" class="absolute bottom-56 right-90 hidden w-14 -rotate-12 md:block z-0 opacity-80" alt="" aria-hidden="true" />
                <img src="/images/confetti_chunk_1.svg" class="absolute bottom-1/2 right-24 hidden w-10 rotate-90 md:block z-0" alt="" aria-hidden="true" />
                    
                <img src="/images/confetti_chunk_3.svg" class="absolute bottom-1/2 right-1/4 hidden w-12 rotate-50 md:block z-0 opacity-60" alt="" aria-hidden="true" />
                <img src="/images/confetti_chunk_2.svg" class="absolute bottom-1/2 left-1/4 hidden w-10 -rotate-45 md:block z-0 opacity-60" alt="" aria-hidden="true" />
                <img src="/images/confetti_chunk_2.svg" class="absolute bottom-1/3 right-30 hidden w-10 -rotate-45 md:block z-0 opacity-60" alt="" aria-hidden="true" />

                <h2 class="mb-8 text-center text-4xl font-bold md:text-6xl">
                    Rankings
                </h2>

                <p class="mx-auto mb-8 max-w-3xl text-center leading-8">
                    See the ranking of other players around the world! There, you can see your own ranking
                    compared to others. Try to aim for the top!
                </p>

                <div class="mb-12 flex justify-center">
                    <Button
                        label="View Rankings"
                        as="router-link"
                        to="/leaderboard"
                        severity="secondary"
                        class="rounded-2xl! px-16! py-4! text-xl! font-semibold! shadow-[0_15px_20px_rgba(0,0,0,0.3)]"
                    />
                </div>

                <div v-if="hasRankings" class="flex flex-1 items-end justify-center">
                    <div v-if="loading" class="mb-10 text-center">
                        Loading...
                    </div>

                    <div v-else class="flex items-end justify-center gap-4 text-black md:gap-14">
                        <!-- 2nd -->
                        <div v-if="bestUsers.leaderboards?.length >= 2" class="flex flex-col items-center justify-end">
                            <div class="relative z-10 -mb-2.5 flex h-20 w-20 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-gradient-to-b from-slate-100 to-slate-400 shadow-lg">
                                <img
                                    v-if="getUserAvatar(1)"
                                    :src="getUserAvatar(1)"
                                    :alt="bestUsers.leaderboards[1]?.user?.name ?? 'Player avatar'"
                                    class="h-full w-full object-cover"
                                    @error="handleAvatarError"
                                />

                                <span v-else class="text-2xl font-bold text-slate-700">
                                    {{ getUserInitial(1) }}
                                </span>
                            </div>

                            <div class="flex min-h-42.5 w-28 flex-col rounded-t-xl bg-gradient-to-b from-slate-100 via-slate-300 to-slate-500 px-4 pt-8 pb-4 text-center text-slate-900 shadow-[inset_0_4px_8px_rgba(255,255,255,0.45),0_12px_18px_rgba(0,0,0,0.25)] md:w-44">
                                <p class="font-bold break-words">
                                    {{ bestUsers.leaderboards[1]?.user?.name ?? "No one :v" }}
                                </p>
                                <p class="mt-2">Wins: {{ bestUsers.leaderboards[1]?.wins ?? "none" }}</p>
                                <p>Points: {{ bestUsers.leaderboards[1]?.points ?? "no pts" }}</p>
                            </div>
                        </div>

                        <!-- 1st -->
                        <div v-if="bestUsers.leaderboards?.length >= 1" class="flex flex-col items-center justify-end">
                            <div class="relative z-10 -mb-2.5 flex h-20 w-20 items-center justify-center overflow-visible">
                                <img
                                    src="/images/crown.svg"
                                    class="absolute -top-8 left-3/5 h-10 w-10 -translate-x-1/2 rotate-12"
                                    alt="Crown"
                                />

                                <div class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-gradient-to-b from-yellow-100 to-yellow-400 shadow-lg">
                                    <img
                                        v-if="getUserAvatar(0)"
                                        :src="getUserAvatar(0)"
                                        :alt="bestUsers.leaderboards[0]?.user?.name ?? 'Player avatar'"
                                        class="h-full w-full object-cover"
                                        @error="handleAvatarError"
                                    />

                                    <span v-else class="text-2xl font-bold text-yellow-950">
                                        {{ getUserInitial(0) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex min-h-[220px] w-28 flex-col rounded-t-xl bg-gradient-to-b from-yellow-200 via-yellow-400 to-amber-700 px-4 pt-8 pb-4 text-center text-yellow-950 shadow-[inset_0_4px_8px_rgba(255,255,255,0.45),0_14px_22px_rgba(0,0,0,0.3)] md:w-48">
                                <p class="font-bold break-words">
                                    {{ bestUsers.leaderboards[0]?.user?.name ?? "There isn't a first player in the ranking" }}
                                </p>
                                <p class="mt-2">Wins: {{ bestUsers.leaderboards[0]?.wins ?? "none" }}</p>
                                <p>Points: {{ bestUsers.leaderboards[0]?.points ?? "none" }}</p>
                            </div>
                        </div>

                        <!-- 3rd -->
                        <div v-if="bestUsers.leaderboards?.length >= 3" class="flex flex-col items-center justify-end">
                            <div class="relative z-10 mb-[-10px] flex h-20 w-20 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-gradient-to-b from-orange-200 to-amber-700 shadow-lg">
                                <img
                                    v-if="getUserAvatar(2)"
                                    :src="getUserAvatar(2)"
                                    :alt="bestUsers.leaderboards[2]?.user?.name ?? 'Player avatar'"
                                    class="h-full w-full object-cover"
                                    @error="handleAvatarError"
                                />

                                <span v-else class="text-2xl font-bold text-orange-950">
                                    {{ getUserInitial(2) }}
                                </span>
                            </div>

                            <div class="flex min-h-[145px] w-28 flex-col rounded-t-xl bg-gradient-to-b from-orange-200 via-orange-500 to-amber-900 px-4 pt-8 pb-4 text-center text-orange-950 shadow-[inset_0_4px_8px_rgba(255,255,255,0.35),0_12px_18px_rgba(0,0,0,0.25)] md:w-44">
                                <p class="font-bold break-words">
                                    {{ bestUsers.leaderboards[2]?.user?.name ?? "There isn't a third player in the ranking" }}
                                </p>
                                <p class="mt-2">Wins: {{ bestUsers.leaderboards[2]?.wins ?? "none" }}</p>
                                <p>Points: {{ bestUsers.leaderboards[2]?.points ?? "none" }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="flex flex-1 items-center justify-center text-center">
                    <p><b>No ranking found</b></p>
                </div>
            </div>
        </section>

        <!-- go to dashboard, old from the template the teachers gave us -->
        <!-- <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
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
        </div> -->
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

const fallbackAvatar = '/images/placeholder.png';

const getUserAvatar = (index) => {
    return bestUsers.value.leaderboards?.[index]?.user?.avatar || fallbackAvatar;
};

const getUserInitial = (index) => {
    return bestUsers.value.leaderboards?.[index]?.user?.name?.charAt(0) ?? '?';
};

const handleAvatarError = (event) => {
    event.target.src = fallbackAvatar;
};
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