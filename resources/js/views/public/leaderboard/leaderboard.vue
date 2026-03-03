<template>
    <div class="card">
        <DataTable :value="leaderBoard" paginator :rows="10" :rowsPerPageOptions="[10, 25, 50, 100]" scrollable scrollHeight="400px" tableStyle="min-width: 50rem">
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

const { getLeaderboards } = useLeaderboards();
const loading = ref(false);

onMounted(async () => {
    loading.value = true;

    try {
        const response = await getLeaderboards();

        leaderBoard.value = response.data;
    } catch (error) {
        console.error("Failed to load leaderboard:", error);
    }finally {
        loading.value = false;
    }
});

const leaderBoard = ref([]);
</script>

