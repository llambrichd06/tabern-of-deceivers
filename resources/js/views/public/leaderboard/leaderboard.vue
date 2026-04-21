<template>
    <div class="page-content min-h-screen bg-[#520B93] text-white">
        <section class="px-4 py-10 md:py-14">
            <div class="mx-auto max-w-5xl">
                <!-- HEADER -->
                <div class="mb-10 text-center">
                    <h1 class="mb-6 text-4xl font-extrabold tracking-tight md:text-6xl">
                        Leaderboard
                    </h1>

                    <p class="mx-auto max-w-3xl leading-8 text-white/90">
                        Check the best players in Tabern of Deceivers. Compare points, wins,
                        losses, and win/loss ratio to see who rules the tavern.
                    </p>
                </div>

                <!-- TABLE CARD -->
                <div class="rounded-3xl bg-purple-300/35 p-4 md:p-8 shadow-[0_15px_20px_rgba(0,0,0,0.3)]">
                    <DataTable
                        :value="leaderboards"
                        lazy
                        paginator
                        :first="first"
                        :rows="rows"
                        :rowsPerPageOptions="[10, 20, 30]"
                        :totalRecords="totalLeaderboards"
                        :loading="loading"
                        scrollable
                        scrollHeight="400px"
                        tableStyle="min-width: 50rem"
                        @page="newPageLoad($event)"
                        class="leaderboard-table"
                    >
                        <Column field="user.name" header="User name"></Column>
                        <Column field="points" header="Points"></Column>
                        <Column field="wins" header="Wins"></Column>
                        <Column field="losses" header="Losses"></Column>

                        <Column header="W/L ratio">
                            <template #body="{ data }">
                                {{ (data.wins / (data.losses == 0 ? 1 : data.losses)).toFixed(2) }}
                            </template>
                        </Column>
                    </DataTable>
                </div>

                <!-- BUTTON -->
                <div class="mt-10 flex justify-center">
                    <Button
                        label="PLAY NOW"
                        as="router-link"
                        to="/rooms"
                        severity="secondary"
                        class="rounded-2xl! px-10! py-4! text-xl! font-semibold! shadow-[0_15px_20px_rgba(0,0,0,0.3)]"
                    />
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import useLeaderboards from "../../../composables/leaderboards";

const { getPaginatedLeaderboards, leaderboards, totalLeaderboards } = useLeaderboards();

const loading = ref(false);
const first = ref(0);
const rows = ref(10);

onMounted(async () => {
    loading.value = true;

    try {
        await getPaginatedLeaderboards(1);
    } catch (error) {
        console.error("Failed to load leaderboard:", error);
    } finally {
        loading.value = false;
    }
});

const newPageLoad = async (event) => {
    loading.value = true;

    try {
        const nextPage = event.first / event.rows + 1;
        first.value = event.first;
        rows.value = event.rows;

        await getPaginatedLeaderboards(nextPage, event.rows);
    } catch (error) {
        console.error("Failed to load leaderboard:", error);
    } finally {
        loading.value = false;
    }
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

/* PrimeVue DataTable custom style */
.leaderboard-table :deep(.p-datatable-table) {
    border-radius: 1rem;
    overflow: hidden;
}

.leaderboard-table :deep(.p-datatable-thead > tr > th) {
    background: rgba(82, 11, 147, 0.9) !important;
    color: white !important;
    border: none !important;
    font-weight: 700;
}

.leaderboard-table :deep(.p-datatable-tbody > tr) {
    background: rgba(255, 255, 255, 0.08);
    color: white;
}

.leaderboard-table :deep(.p-datatable-tbody > tr:nth-child(even)) {
    background: rgba(255, 255, 255, 0.12);
}

.leaderboard-table :deep(.p-datatable-tbody > tr > td) {
    border-color: rgba(255, 255, 255, 0.12);
}

.leaderboard-table :deep(.p-paginator) {
    background: transparent;
    border: none;
    color: white;
    padding-top: 1rem;
}

.leaderboard-table :deep(.p-paginator .p-paginator-page),
.leaderboard-table :deep(.p-paginator .p-paginator-next),
.leaderboard-table :deep(.p-paginator .p-paginator-prev),
.leaderboard-table :deep(.p-paginator .p-paginator-first),
.leaderboard-table :deep(.p-paginator .p-paginator-last) {
    color: white;
}

/* ACTIVE PAGE (circle) */
.leaderboard-table :deep(.p-paginator .p-highlight) {
    background: rgba(82, 11, 147, 0.9) !important;
    color: white !important;
    border-radius: 9999px !important;
}

/* IMPORTANT: inner button fix */
.leaderboard-table :deep(.p-paginator .p-paginator-page.p-highlight) {
    background: rgba(82, 11, 147, 0.9) !important;
    color: white !important;
    border-radius: 9999px !important;
}

/* remove that pale green/white look */
.leaderboard-table :deep(.p-paginator .p-paginator-page) {
    background: transparent !important;
    color: white !important;
}

.leaderboard-table :deep(.p-dropdown),
.leaderboard-table :deep(.p-select) {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.2);
    color: white;
}

.leaderboard-table :deep(.p-datatable-loading-overlay) {
    background: rgba(82, 11, 147, 0.35);
}

.leaderboard-table :deep(.p-datatable-wrapper) {
    border-radius: 1rem;
}
</style>