<template>
    <Card>
        <template #title>
            <div class="flex items-center justify-between w-full">
                <span>Gestión de Games</span>
                <div class="flex items-center gap-2">
                    <Button 
                        label="Actualizar" 
                        icon="pi pi-refresh" 
                        size="small" 
                        outlined 
                        severity="secondary" 
                        :loading="loading" 
                        @click="refreshGames" 
                    />
                    <Button 
                        v-if="can('game-create')"
                        label="Nuevo Game" 
                        icon="pi pi-plus" 
                        size="small" 
                        severity="primary" 
                        @click="router.push('/admin/games/create')" 
                    />
                </div>
            </div>
        </template>

        <template #subtitle class="">Administra y gestiona los games del sistema. Crea, edita y elimina games.</template>

        <template #content>
            <DataTable
                v-model:filters="filters"
                v-model:sort-field="sortField"
                v-model:sort-order="sortOrder"
                :value="games || []"
                :paginator="true"
                :rows="10"
                :rows-per-page-options="[10, 25, 50]"
                data-key="id"
                striped-rows
                size="small"
                :loading="loading"
                filter-display="menu"
                :filter-delay="300"
                :global-filter-fields="['room_id', 'is_finished', 'game_state']"

            >
                <Column field="id" header="ID" sortable class="w-[60px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="3rem" height="1rem" />
                        <span v-else class="table-cell-id">{{ slotProps.data.id }}</span>
                    </template>
                </Column>

                <Column field="room_id" header="room_id" sortable :filter-placeholder="'room_id'" class="min-w-[200px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="10rem" height="1rem" />
                        <div v-else class="flex items-center space-x-2">
                            <i class="pi pi-game text-blue-600" />
                            <span class="font-medium table-cell-name">{{ slotProps.data.room_id || '-' }}</span>
                        </div>
                    </template> 
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="room_id" class="w-full" />
                    </template>
                </Column>

                <Column field="is_finished" header="is_finished" sortable :filter-placeholder="'is_finished'" class="min-w-[150px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="8rem" height="1rem" />
                        <span v-else class="table-cell-name">{{ slotProps.data.is_finished || '-' }}</span>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="is_finished" class="w-full" />
                    </template>
                </Column>

                <Column field="game_state" header="game_state" sortable :filter-placeholder="'game_state'" class="min-w-[200px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="12rem" height="1rem" />
                        <span v-else class="text-sm table-cell-email">{{ slotProps.data.game_state || '-' }}</span>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="game_state" class="w-full" />
                    </template>
                </Column>

                <Column field="host_id" header="host_id" sortable  class="min-w-[150px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="8rem" height="1rem" />
                        <span v-else class="text-sm table-cell-date">{{ slotProps.data.host_id }}</span>
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" placeholder="host_id" class="w-full" />
                    </template>
                </Column>

                <Column v-if="can('game-edit') || can('game-delete')" header="Avaliable actions" class="w-[150px]">
                    <template #body="slotProps">
                        <Skeleton v-if="loading" width="4rem" height="2rem" />
                        <div v-else class="flex gap-2">
                            <Button
                                v-if="can('game-edit')"
                                v-tooltip.top="'Editar game'"
                                icon="pi pi-pencil"
                                rounded
                                text
                                severity="secondary"
                                size="small"
                                @click="router.push({ name: 'gamesAdmin.edit', params: { id: slotProps.data.id } })"
                            />
                            <Button
                                v-if="can('game-delete')"
                                v-tooltip.top="'Eliminar game'"
                                icon="pi pi-trash"
                                rounded
                                text
                                severity="danger"
                                size="small"
                                @click="deleteGame(slotProps.data.id, slotProps.index)"
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
import useGames from "../../../composables/games";
import { useAbility } from '@casl/vue';
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import { useGameCrudStore } from "../../../store/game";
import { storeToRefs } from "pinia";

const router = useRouter();
const { games, getGames, deleteGame } = useGames();
const { can } = useAbility();
const loading = ref(false);

const store = useGameCrudStore();
//these are setted as v-model on the datatable, so they automatically update
const { filters, sortField, sortOrder } = storeToRefs(store);

const refreshGames = () => {
    loading.value = true;
    getGames().finally(() => {
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
    getGames().finally(() => {
        loading.value = false;
    });
});
</script>
