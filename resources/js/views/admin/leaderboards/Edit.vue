<template>
    <div class="show-d"></div>
    <div class="grid grid-flow-col auto-rows-min gap-5">
        <Panel class="col-span-12" pt:content:class="flex flex-col gap-10 justify-between">
            <!-- <template #header>
                <h5 class="leaderboard-name text-2xl font-bold mb-1">{{ leaderboard.name }}</h5>
            </template> -->
            <div>
                <h6 class="mb-4 text-lg font-bold">Leaderboard data</h6>
                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="user_id">User id:</label>
                        <InputText 
                            v-model="leaderboard.user_id" 
                            type="number" 
                            size="small" 
                            id="user_id" 
                            :class="{ 'p-invalid': hasError('user_id') }"
                        />
                    </div>
                    <small v-if="hasError('user_id')" class="p-error">
                        {{ getError('user_id') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="points">Points:</label>
                        <InputText 
                            v-model="leaderboard.points" 
                            size="small" 
                            type="number" 
                            id="points" 
                            :class="{ 'p-invalid': hasError('points') }"
                        />
                    </div>
                    <small v-if="hasError('points')" class="p-error">
                        {{ getError('points') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="wins">Wins:</label>
                        <InputText 
                            v-model="leaderboard.wins" 
                            type="number" 
                            size="small" 
                            id="wins" 
                            :class="{ 'p-invalid': hasError('wins') }"
                        />
                    </div>
                    <small v-if="hasError('wins')" class="p-error">
                        {{ getError('wins') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="losses">Losses:</label>
                        <InputText 
                            v-model="leaderboard.losses" 
                            type="number" 
                            size="small" 
                            id="losses" 
                            :class="{ 'p-invalid': hasError('losses') }"
                        />
                    </div>
                    <small v-if="hasError('losses')" class="p-error">
                        {{ getError('losses') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="matches">Matches:</label>
                        <InputText 
                            v-model="leaderboard.matches" 
                            type="number" 
                            size="small" 
                            id="matches" 
                            :class="{ 'p-invalid': hasError('matches') }"
                        />
                    </div>
                    <small v-if="hasError('matches')" class="p-error">
                        {{ getError('matches') }}
                    </small>
                </div>
            </div>
            <div class="text-right self-end">
                <Button :disabled="isLoading" @click="submitForm" :loading="isLoading">
                    <span v-if="!isLoading">Guardar</span>
                    <span v-else>Guardando...</span>
                </Button>
            </div>

        </Panel>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { usePrimeVue } from 'primevue/config';
import useLeaderboards from "@/composables/leaderboards";

const $primevue = usePrimeVue();
const route = useRoute();

const {leaderboard, getLeaderboard, updateLeaderboard, isLoading, hasError, getError,setLeaderboard } = useLeaderboards();

const submitForm = async () => {
    try {
        await updateLeaderboard(leaderboard.value);
    } catch (e) {
        
        // Errors handled by composable (toast)
    }
}

onMounted(async () => {
    await getLeaderboard(route.params.id);
})

// File Upload Logic
// const totalSize = ref(0);
// const totalSizePercent = ref(0);
// const files = ref([]);

// const onBeforeUpload = (event) => {
//     event.formData.append('id', leaderboard.value.id)
// };

// const onSelectedFiles = (event) => {
//     files.value = event.files;
//     if (event.files.length > 1) {
//         event.files = event.files.splice(0, event.files.length - 1);
//     }
//     files.value.forEach((file) => {
//         totalSize.value += parseInt(formatSize(file.size));
//     });
// };

// const uploadEvent = async (callback, uploadedFiles) => {
//     totalSizePercent.value = totalSize.value / 10;
//     await callback();
// };

// const onTemplatedUpload = (event) => {
//     // Reload leaderboard to get new avatar
//     getLeaderboard(leaderboard.value.id);
// };

// const formatSize = (bytes) => {
//     const k = 1024;
//     const dm = 3;
//     const sizes = $primevue.config.locale.fileSizeTypes;

//     if (bytes === 0) {
//         return `0 ${sizes[0]}`;
//     }

//     const i = Math.floor(Math.log(bytes) / Math.log(k));
//     const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm));

//     return `${formattedSize} ${sizes[i]}`;
// };

</script>

<style>
.fu-content {
    padding: 0px !important;
    border: 0px !important;
    border-radius: 6px;
}

.fu-header {
    border: 0px !important;
    border-radius: 6px;
}

.fu {
    display: flex;
    flex-direction: column-reverse;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}
</style>
