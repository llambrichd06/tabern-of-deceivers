<template>
    <!-- HERO -->
    <section
        class="relative overflow-hidden rounded-xl border border-purple-700 min-h-[280px] md:min-h-[320px]"
    >
        <!-- Fallback background color -->
        <div class="absolute inset-0 bg-emerald-900"></div>

        <!-- GIF background -->
        <div
            class="absolute inset-0 bg-center bg-cover bg-no-repeat"
            style="background-image: url('/images/tabern-background.gif');"
        ></div>

        <!-- Left dark shadow -->
        <div
            class="absolute inset-y-0 left-0 w-full md:w-[42%] bg-gradient-to-r from-black/70 via-black/45 to-transparent z-10"
        ></div>

        <!-- Hero content -->
        <div class="relative z-20 flex min-h-[280px] md:min-h-[320px] items-center">
            <div class="grid w-full grid-cols-1 md:grid-cols-[240px_1fr] px-6 pt-20 pb-8 md:px-10 md:pt-8">
                <!-- Left text -->
                <div class="flex flex-col justify-center text-white max-w-[220px]">
                    <p class="text-2xl md:text-3xl font-semibold leading-tight mb-4">
                        Lie, decieve and scheme!
                    </p>
                    <p class="text-base md:text-lg leading-snug text-white/95">
                        In the tavern of deceivers the best liar is the winner!
                    </p>
                </div>

                <!-- Center / right title + button -->
                <div class="flex flex-col justify-center items-start md:items-center mt-8 md:mt-0">
                    <h1
                        class="text-white text-4xl md:text-6xl font-extrabold tracking-tight mb-6 text-left md:text-center"
                    >
                        Tabern of Deceivers
                    </h1>

                    <Button
                        label="PLAY NOW"
                        as="router-link"
                        to="/rooms"
                        class="!rounded-full !border-0 !px-10 !py-4 !text-2xl !font-extrabold !tracking-[0.2em] !uppercase hero-play-btn"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="mt-10 rounded-xl px-6 py-8 md:px-10 md:py-10">
        <div class="mx-auto max-w-5xl">
            <h2 class="mb-6 text-center text-2xl font-bold">
                How does the game work?
            </h2>

            <p class="mx-auto mb-8 max-w-3xl text-center text-base leading-8">
                This is a game about lying to others! To be able to win you will need to lie,
                decive and predict the other’s moves while trying to keep your friendships intact.
            </p>

            <div class="grid items-center gap-8 md:grid-cols-2">
                <!-- Left side -->
                <div>
                    <p class="mb-4 text-lg leading-8">
                        Some features you will discover about the game:
                    </p>

                    <ul class="space-y-2 text-lg">
                        <li>2-6 player matches</li>
                        <li>Easy to learn, hard to master</li>
                        <li>A mix of luck and strategy</li>
                    </ul>

                    <div class="mt-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <h3 class="text-2xl font-bold">
                            Read some more detailed rules here:
                        </h3>

                        <Button
                            label="How To Play"
                            class="!rounded-2xl !px-8 !py-3"
                        />
                    </div>
                </div>

                <!-- Right side placeholder -->
                <div class="flex justify-center md:justify-end">
                    <div
                        class="flex h-[180px] w-full max-w-[260px] items-center justify-center rounded-md border border-surface-300 bg-surface-100 text-center text-sm text-surface-500"
                    >
                        Game image placeholder
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BEST PLAYERS -->
    <section class="mt-12 px-4">
        <div class="mx-auto max-w-5xl rounded-2xl bg-purple-300/60 px-6 py-8 shadow-lg md:px-10 md:py-10">
            <h2 class="mb-8 text-center text-2xl font-bold">
                Rankings
            </h2>

            <p class="mx-auto mb-8 max-w-3xl text-center text-base leading-8">
                See the ranking of other players around the world! There, you can see your own ranking
                compared to others. Try to aim for the top!
            </p>

            <div class="mb-12 flex justify-center">
                <Button
                    label="View Rankings"
                    class="!rounded-2xl !px-8 !py-3"
                />
            </div>

            <div v-if="rankings != 0">
                <div v-if="loading" class="text-center">
                    Loading...
                </div>

                <div v-else class="flex items-end justify-center gap-4 md:gap-10">
                    <!-- 2nd place -->
                    <div class="flex flex-col items-center justify-end">
                        <div class="h-20 w-20 rounded-full border border-black bg-surface-200"></div>

                        <div class="mt-2 flex h-24 w-24 flex-col justify-end rounded-t-md border border-black bg-orange-500 px-2 pb-2 text-center md:h-28 md:w-28">
                            <p class="text-xs font-bold md:text-sm">
                                {{ bestUsers.leaderboards[1]?.user?.name ?? "No one :v" }}
                            </p>
                            <p class="text-[10px] md:text-xs">
                                Wins: {{ bestUsers.leaderboards[1]?.wins ?? "none" }}
                            </p>
                            <p class="text-[10px] md:text-xs">
                                Points: {{ bestUsers.leaderboards[1]?.points ?? "no pts" }}
                            </p>
                        </div>
                    </div>

                    <!-- 1st place -->
                    <div class="flex flex-col items-center justify-end">
                        <div class="mb-2 text-4xl leading-none">👑</div>

                        <div class="h-20 w-20 rounded-full border border-black bg-surface-200"></div>

                        <div class="mt-2 flex h-36 w-24 flex-col justify-end rounded-t-md border border-black bg-yellow-300 px-2 pb-2 text-center md:h-40 md:w-28">
                            <p class="text-xs font-bold md:text-sm">
                                {{ bestUsers.leaderboards[0]?.user?.name ?? "No one :v" }}
                            </p>
                            <p class="text-[10px] md:text-xs">
                                Wins: {{ bestUsers.leaderboards[0]?.wins ?? "none" }}
                            </p>
                            <p class="text-[10px] md:text-xs">
                                Points: {{ bestUsers.leaderboards[0]?.points ?? "no pts" }}
                            </p>
                        </div>
                    </div>

                    <!-- 3rd place -->
                    <div class="flex flex-col items-center justify-end">
                        <div class="h-20 w-20 rounded-full border border-black bg-surface-200"></div>

                        <div class="mt-2 flex h-20 w-24 flex-col justify-end rounded-t-md border border-black bg-slate-200 px-2 pb-2 text-center md:h-24 md:w-28">
                            <p class="text-xs font-bold md:text-sm">
                                {{ bestUsers.leaderboards[2]?.user?.name ?? "No one :v" }}
                            </p>
                            <p class="text-[10px] md:text-xs">
                                Wins: {{ bestUsers.leaderboards[2]?.wins ?? "none" }}
                            </p>
                            <p class="text-[10px] md:text-xs">
                                Points: {{ bestUsers.leaderboards[2]?.points ?? "no pts" }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center">
                <p><b>No ranking found</b></p>
            </div>
        </div>
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
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
.hero-play-btn {
    background: linear-gradient(90deg, #8dd0ee 0%, #2fd3e6 100%) !important;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    color: white !important;
};
</style>