<template>
    <div class="page-content min-h-screen bg-[#520B93] text-white">
        <section class="px-4 pt-2 pb-10 md:pt-1 md:pb-14">
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
                        @sort="newPageLoad($event)"
                        :sortField="sortField"
                        :sortOrder="sortOrder"
                        class="leaderboard-table"
                    >
                        <Column field="user.name" header="User name" sortable></Column>
                        <Column field="points" header="Points" sortable></Column>
                        <Column field="wins" header="Wins" sortable></Column>
                        <Column field="losses" header="Losses" sortable></Column>

                        <Column header="W/L ratio">
                            <template #body="{ data }">
                                {{ (data.wins / (data.losses == 0 ? 1 : data.losses)).toFixed(2) }}
                            </template>
                        </Column>
                    </DataTable>
                </div>
                <div class="mt-6 rounded-2xl border border-white/10 bg-purple-300/20 p-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:gap-4">
                        <!-- Search By -->
                        <div class="flex flex-col gap-1 w-full md:w-[180px]">
                            <label class="ml-1 text-xs font-bold uppercase tracking-wider text-white">
                                Search By
                            </label>

                            <Select
                                v-model="filterField"
                                :options="filterOptions"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="Select Column"
                                class="w-full custom-select h-[62px] md:h-[62px]"
                                @change="onFilter"
                            />
                        </div>

                        <!-- Search Value -->
                        <div class="flex flex-col gap-1 w-full md:flex-1">
                            <label class="ml-1 text-xs font-bold uppercase tracking-wider text-white">
                                Search Value
                            </label>

                            <IconField>
                                <InputIcon class="pi pi-search text-white/70" />
                                <InputText
                                    v-model="filterValue"
                                    placeholder="Type to filter..."
                                    class="w-full custom-input h-[62px] md:h-[62px]"
                                    @input="onFilter"
                                />
                            </IconField>
                        </div>

                        <!-- Clear -->
                        <div class="flex flex-col justify-end w-full md:w-[96px]">
                            <Button
                                icon="pi pi-filter-slash"
                                label="Clear"
                                outlined
                                class="w-full custom-clear-btn h-[62px] md:h-[62px]"
                                @click="clearFilter"
                            />
                        </div>
                    </div>
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

// Rows
const first = ref(0);
const rows = ref(10);

// Sorting
const sortField = ref('points'); // Default sort points
const sortOrder = ref(-1);    // Default desc

// Filtering
const filterValue = ref('');
const filterField = ref('user.name'); 
const filterOptions = [
    { label: 'User Name', value: 'user.name' },
    { label: 'Points', value: 'points' },
    { label: 'Wins', value: 'wins' },
    { label: 'Losses', value: 'losses' }
];

let debounceTimer = null;

onMounted(async () => {
    loading.value = true;

    try {
        await getPaginatedLeaderboards(1, sortField.value, sortOrder.value, rows.value);
    } catch (error) {
        console.error("Failed to load leaderboard:", error);
    } finally {
        loading.value = false;
    }
});

const onFilter = () => {
    clearTimeout(debounceTimer);
    // wait 500 ms to do the api call
    debounceTimer = setTimeout(() => {
        newPageLoad({ first: 0, rows: rows.value, sortField: sortField.value, sortOrder: sortOrder.value });
    }, 500);
};

const clearFilter = () => {
    filterValue.value = '';
    newPageLoad({ first: 0, rows: rows.value, sortField: sortField.value, sortOrder: sortOrder.value });
};

const newPageLoad = async (event) => {
    loading.value = true;

    try {
        const nextPage = event.first / event.rows + 1;
        first.value = event.first;
        rows.value = event.rows;
        sortField.value = event.sortField ?? 'points';
        sortOrder.value = event.sortOrder ?? -1;
        filterField.value,
        filterValue.value,

        await getPaginatedLeaderboards(
            nextPage,
            sortField.value,
            sortOrder.value,
            filterField.value,
            filterValue.value,
            event.rows,
        );
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

/* ACTIVE PAGE */
.leaderboard-table :deep(.p-paginator .p-highlight),
.leaderboard-table :deep(.p-paginator .p-paginator-page.p-highlight) {
    background: rgba(82, 11, 147, 0.9) !important;
    color: white !important;
    border-radius: 9999px !important;
}

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

/* ============================= */
/* INPUT & SELECT (FIX FINAL) */
/* ============================= */

:deep(.custom-input),
:deep(.custom-select) {
    background: rgba(255, 255, 255, 0.12) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    border-radius: 0.75rem !important;
}

/* INPUT */
:deep(.custom-input) {
    height: 62px !important;
    padding: 0 0.75rem;
}

/* SELECT BASE */
:deep(.custom-select) {
    height: 62px !important;
}

/* SELECT TEXT (CENTRADO + COLOR) */
:deep(.custom-select .p-select-label) {
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0 0.75rem;
    color: white !important;
}

/* ICONO SELECT */
:deep(.custom-select .p-select-trigger) {
    display: flex;
    align-items: center;
    color: white !important;
}

/* FOCUS */
:deep(.custom-input:focus),
:deep(.custom-select:focus) {
    border-color: #a855f7 !important;
    box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.4) !important;
}

/* DROPDOWN */
:deep(.p-select-overlay) {
    background: #3b0764 !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

:deep(.p-select-option) {
    color: white !important;
}

:deep(.p-select-option:hover) {
    background: rgba(255, 255, 255, 0.1) !important;
}

/* CLEAR BUTTON */
:deep(.custom-clear-btn) {
    border-color: rgba(255, 255, 255, 0.4) !important;
    color: white !important;
    border-radius: 0.75rem !important;
    height: 62px !important;
}

:deep(.custom-clear-btn:hover) {
    background: rgba(255, 255, 255, 0.1) !important;
}

:deep(.custom-input::placeholder) {
    color: rgba(255, 255, 255, 0.85) !important;
    opacity: 1 !important;
}

:deep(.custom-select .p-select-label.p-placeholder) {
    color: rgba(255, 255, 255, 0.85) !important;
}
</style>