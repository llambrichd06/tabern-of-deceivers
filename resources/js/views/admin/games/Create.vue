<template>
    <Panel class="flex flex-col justify-center my-10">
        <form @submit.prevent="submitForm">

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="game-room_id">room_id:</label>
                    <InputText v-model="game.room_id" id="game-room_id" type="text" size="small" :invalid="!!errors.room_id" />
                </div>
                <div class="text-red-400 mt-1">
                    {{ errors.room_id }}
                </div>
                <div class="mt-1">
                    <div v-for="message in validationErrors?.room_id" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="game-is_finished">Is_finished:</label>
                    <Checkbox
                        v-model="game.is_finished"
                        :binary="true"
                        inputId="game-is_finished"
                    />
                </div>
                <div class="text-red-400 mt-1">
                    {{ errors.is_finished }}
                </div>
                <div class="mt-1">
                    <div v-for="message in validationErrors?.is_finished" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="game-game_state">game_state:</label>
                    <InputText v-model="game.game_state" id="game-game_state" type="game_state" size="small" :invalid="!!errors.game_state"/>
                </div>
                <div class="text-red-400 mt-1">
                    {{ errors.game_state }}
                </div>
                <div class="mt-1">
                    <div v-for="message in validationErrors?.game_state" class="text-red-400">
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
    import { onMounted, ref } from "vue";
    import useGames from "@/composables/games";

    const { game, storeGame, validationErrors, isLoading, errors } = useGames();

    function submitForm() {
        storeGame(game.value)
    }
</script>
