<template>
    <div class="show-d"></div>
    <div class="grid grid-flow-col auto-rows-min gap-5">
        <Panel class="col-span-12" pt:content:class="flex flex-col gap-10 justify-between">
            <template #header>
                <h5 class="game-name text-2xl font-bold mb-1">Game {{ game.room_id }}</h5>
            </template>
            <div>
                <h6 class="mb-4 text-lg font-bold">Datos personales</h6>
                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="room_id">room_id:</label>
                        <InputText 
                            v-model="game.room_id" 
                            type="text" 
                            size="small" 
                            id="room_id" 
                            :class="{ 'p-invalid': hasError('room_id') }"
                        />
                    </div>
                    <small v-if="hasError('room_id')" class="p-error">
                        {{ getError('room_id') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="is_finished">Is_finished:</label>
                        <Checkbox
                            v-model="game.is_finished"
                            :binary="true"
                            inputId="is_finished"
                            :class="{ 'p-invalid': hasError('is_finished') }"
                        />
                    </div>
                    <small v-if="hasError('is_finished')" class="p-error">
                        {{ getError('is_finished') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="game_state">game_state:</label>
                        <InputText 
                            v-model="game.game_state" 
                            type="game_state" 
                            size="small" 
                            id="game_state" 
                            :class="{ 'p-invalid': hasError('game_state') }"
                        />
                    </div>
                    <small v-if="hasError('game_state')" class="p-error">
                        {{ getError('game_state') }}
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
import useGames from "@/composables/games";

const $primevue = usePrimeVue();
const route = useRoute();

const {game, getGame, updateGame, isLoading, hasError, getError,setGame } = useGames();

const submitForm = async () => {
    try {
        await updateGame(game.value);
    } catch (e) {
        // Errors handled by composable (toast)
    }
}

onMounted(async () => {
    const gameData = await getGame(route.params.id);
})

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
