<template>
    <div class="card">
        <DataTable :value="leaderboards" lazy paginator :first="first" :rows="rows" :rowsPerPageOptions="[10,20,30]" :totalRecords="totalLeaderboards" :loading="loading" scrollable scrollHeight="400px" tableStyle="min-width: 50rem" @page="newPageLoad($event)">
            <Column field="user.name" header="User name"></Column>
            <Column field="points" header="points"></Column>
            <Column field="wins" header="wins"></Column>
            <Column field="losses" header="losses"></Column>
            <Column header="W/L ratio">
                <template #body="{ data }">
                    {{ (data.wins / (data.losses == 0 ? 1 : data.losses)).toFixed(2) }}
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import useLeaderboards from "../../../composables/leaderboards";

const { getPaginatedLeaderboards, leaderboards, totalLeaderboards} = useLeaderboards();
const loading = ref(false);
const first = ref(0);
const rows = ref(10);
onMounted(async () => {
    loading.value = true;

    try {
        // await getLeaderboards();
        await getPaginatedLeaderboards(1).then( r => {
            console.log(r)
        });
        console.log(leaderboards)
        console.log(totalLeaderboards)
    } catch (error) {
        console.error("Failed to load leaderboard:", error);
    }finally {
        loading.value = false;
    }
});

// the page event returns:
// {
//     first: 20,    The index of the first record (0-based)
//     rows: 10,     How many rows are shown per page
//     page: 2,      The zero-indexed page number (Page 3 = index 2)
//     pageCount: 5  Total number of pages
// }
const newPageLoad = async (event) => {
    loading.value = true;
    console.log(event)
    try {
        const nextPage = (event.first / event.rows)+1;
        first.value = event.first;
        rows.value = event.rows;
        await getPaginatedLeaderboards(nextPage, event.rows);
    } catch (error) {
        console.error("Failed to load leaderboard:", error);
    }finally {
        loading.value = false;
    }
}

</script>

