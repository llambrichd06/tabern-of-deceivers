<template>
    <div class="show-d"></div>
    <div class="grid grid-flow-col auto-rows-min gap-5">
        <Panel class="col-span-12" pt:content:class="flex flex-col gap-10 justify-between">
            <template #header>
                <h5 class="room-name text-2xl font-bold mb-1">{{ room.room_code }}</h5>
            </template>
            <div>
                <h6 class="mb-4 text-lg font-bold">Datos personales</h6>
                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="room_code">Room code:</label>
                        <InputText 
                            v-model="room.room_code" 
                            type="text" 
                            size="small" 
                            id="room_code" 
                            :class="{ 'p-invalid': hasError('room_code') }"
                        />
                    </div>
                    <small v-if="hasError('room_code')" class="p-error">
                        {{ getError('room_code') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="state">State:</label>
                        <Select
                            v-model="room.state"
                            :options="posibleStates"
                            optionLabel="name"
                            optionValue="code"
                            class="w-full md:w-56"
                        />
                        
                    </div>
                    <small v-if="hasError('state')" class="p-error">
                        {{ getError('state') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="private">Private:</label>
                        <Checkbox
                            v-model="room.private"
                            :binary="true"
                            inputId="private"
                            :class="{ 'p-invalid': hasError('private') }"
                        />
                    </div>
                    <small v-if="hasError('private')" class="p-error">
                        {{ getError('private') }}
                    </small>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <label for="host_id">Host id:</label>
                        <InputText 
                            v-model="room.host_id" 
                            type="host_id" 
                            size="small" 
                            id="host_id" 
                            :class="{ 'p-invalid': hasError('host_id') }"
                        />
                    </div>
                    <small v-if="hasError('host_id')" class="p-error">
                        {{ getError('host_id') }}
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
import useRooms from "@/composables/rooms";

const $primevue = usePrimeVue();
const route = useRoute();

const {room, getRoom, updateRoom, isLoading, hasError, getError,setRoom } = useRooms();

const submitForm = async () => {
    try {
        await updateRoom(room.value);
    } catch (e) {
        // Errors handled by composable (toast)
    }
}

onMounted(async () => {
    const roomData = await getRoom(route.params.id);
})

const posibleStates = ref([
    { name: 'Lobby', code: 'lobby' },
    { name: 'On_going', code: 'on_going' },
    { name: 'Completed', code: 'completed' },
]);

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
