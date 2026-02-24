<template>
    <Panel class="flex flex-col justify-center my-10">
        <form @submit.prevent="submitForm">

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="room-room_code">room_code:</label>
                    <InputText v-model="room.room_code" id="room-room_code" type="text" size="small" :invalid="!!errors.room_code" />
                </div>
                <div class="text-red-400 mt-1">
                    {{ errors.room_code }}
                </div>
                <div class="mt-1">
                    <div v-for="message in validationErrors?.room_code" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="room-state">state:</label>
                    <InputText v-model="room.state" id="room-state" type="text" size="small" :invalid="!!errors.state" />
                </div>
                <div class="text-red-400 mt-1">
                    {{ errors.state }}
                </div>
                <div class="mt-1">
                    <div v-for="message in validationErrors?.state" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="room-private">Private:</label>
                    <InputText v-model="room.private" id="room-private" type="private" size="small" :invalid="!!errors.private"/>
                </div>
                <div class="text-red-400 mt-1">
                    {{ errors.private }}
                </div>
                <div class="mt-1">
                    <div v-for="message in validationErrors?.private" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="room-host_id">host_id:</label>
                    <InputText v-model="room.host_id" id="room-host_id" type="host_id" size="small" :invalid="!!errors.host_id"/>
                </div>
                <div class="text-red-400 mt-1">
                    {{ errors.host_id }}
                </div>
                <div class="mt-1">
                    <div v-for="message in validationErrors?.host_id" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div> 

            <!-- Buttons -->
            <div class="mt-4 text-right">
                <Button :disabled="isLoading" type="submit">
                    <div v-show="isLoading" class=""></div>
                    <span v-if="isLoading">Processing...</span>
                    <span v-else>Guardar</span>
                </Button>
            </div>
        </form>
    </Panel>
</template>
<script setup>
    import { onMounted } from "vue";
    import useRooms from "@/composables/rooms";

    const { room, storeRoom, validationErrors, isLoading, errors } = useRooms();

    function submitForm() {
        storeRoom(room.value)
    }

</script>
