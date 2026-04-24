<template>
    <Card>
        <template #title>
            <div class="flex items-center justify-between w-full">
                <span>Leaderboard Management</span>
                <div class="flex items-center gap-2">
                    <Button 
                        label="Refresh" 
                        icon="pi pi-refresh" 
                        size="small" 
                        outlined 
                        severity="secondary" 
                        :loading="loading" 
                        @click="refreshLeaderboard" 
                    />
                    <Button 
                        v-if="can('leaderboard-create')"
                        label="New Leaderboard" 
                        icon="pi pi-plus" 
                        size="small" 
                        severity="primary" 
                        @click="router.push('/admin/leaderboards/create')" 
                    />
                </div>
            </div>
        </template>

        <template #subtitle>Manage system leaderboards. Create, edit, and delete leaderboards according to your permissions.</template>

        <template #content>
            <DataTable
                v-model:filters="filters"
                v-model:sort-field="sortField"
                v-model:sort-order="sortOrder"
                :value="leaderboards || []"
                :paginator="true"
                :rows="10"
                :rows-per-page-options="[10, 25, 50]"
                data-key="id"
                striped-rows
                size="small"
                :loading="loading"
                filter-display="menu"
                :filter-delay="300"
                :global-filter-fields="['user_id', 'points', 'wins']"

            >
                <Column field="id" header="ID" sortable class="w-[60px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="3rem" height="1rem" />
                        <span v-else class="table-cell-id">{{ slotProps.data.id }}</span>
                    </template>
                </Column>

                <Column field="user_id" header="User ID" sortable filter :filter-placeholder="'User ID'" class="min-w-[200px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="10rem" height="1rem" />
                        <div v-else class="flex items-center space-x-2">
                            <i class="pi pi-user text-blue-600" />
                            <span class="font-medium table-cell-name">{{ slotProps.data.user_id || '-' }}</span>
                        </div>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="User ID" class="w-full" />
                    </template>
                </Column>

                <Column field="points" header="Points" sortable filter :filter-placeholder="'Points'" class="min-w-[150px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="8rem" height="1rem" />
                        <span v-else class="table-cell-name">{{ slotProps.data.points || '-' }}</span>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="Points" class="w-full" />
                    </template>
                </Column>

                <Column field="wins" header="Matches Won" sortable filter :filter-placeholder="'Matches Won'" class="min-w-[200px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="12rem" height="1rem" />
                        <span v-else class="text-sm table-cell-email">{{ slotProps.data.wins || '-' }}</span>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="Matches Won" class="w-full" />
                    </template>
                </Column>

                <Column field="losses" header="Matches Lost" sortable filter :filter-placeholder="'Matches Lost'" class="min-w-[200px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="12rem" height="1rem" />
                        <span v-else class="text-sm table-cell-email">{{ slotProps.data.losses || '-' }}</span>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="Matches Lost" class="w-full" />
                    </template>
                </Column>

                <Column field="matches" header="Matches played" sortable filter :filter-placeholder="'Matches played'" class="min-w-[200px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="12rem" height="1rem" />
                        <span v-else class="text-sm table-cell-email">{{ slotProps.data.matches || '-' }}</span>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="Matches played" class="w-full" />
                    </template>
                </Column>

                <Column field="created_at" header="Creation date" sortable class="min-w-[150px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="8rem" height="1rem" />
                        <span v-else class="text-sm table-cell-date">{{ formatDate(slotProps.data.created_at) }}</span>
                    </template>
                </Column>

                <Column field="updated_at" header="Last updated" sortable class="min-w-[150px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="8rem" height="1rem" />
                        <span v-else class="text-sm table-cell-date">{{ formatDate(slotProps.data.updated_at) }}</span>
                    </template>
                </Column>

                <Column v-if="can('leaderboard-edit') || can('leaderboard-delete')" header="Avaliable actions" class="w-[150px]" >
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="4rem" height="2rem" />
                        <div v-else class="flex gap-2">
                            <Button
                                v-if="can('leaderboard-edit')"
                                v-tooltip.top="'Edit leaderboard'"
                                icon="pi pi-pencil"
                                rounded
                                text
                                severity="secondary"
                                size="small"
                                @click="router.push({ name: 'leaderboards.edit', params: { id: slotProps.data.id } })"
                            />
                            <Button
                                v-if="can('leaderboard-delete')"
                                v-tooltip.top="'Delete leaderboard'"
                                icon="pi pi-trash"
                                rounded
                                text
                                severity="danger"
                                size="small"
                                @click="deleteLeaderboard(slotProps.data.id

                                )"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import useLeaderboards from "../../../composables/leaderboards";
import { useAbility } from '@casl/vue';
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import { useLeaderboardCrudStore } from "../../../store/leaderboard";
import { storeToRefs } from "pinia";

const router = useRouter();
const { leaderboards, getLeaderboards, deleteLeaderboard } = useLeaderboards();
const { can } = useAbility();
const loading = ref(false);

//initialise the leaderboard crud store
const store = useLeaderboardCrudStore();
//these are setted as v-model on the datatable, so they automatically update
const { filters, sortField, sortOrder } = storeToRefs(store);

const refreshLeaderboard = () => {
    loading.value = true;
    getLeaderboards().finally(() => {
        loading.value = false;
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

onMounted(() => {
    loading.value = true;
    getLeaderboards().finally(() => {
        loading.value = false;
    });
});
</script>
