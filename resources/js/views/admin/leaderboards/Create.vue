<template>
    <Panel class="flex flex-col justify-center my-10">
        <form @submit.prevent="submitForm">
            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="user-name">User Id:</label>
                    <InputText v-model="leaderboard.user_id" id="user-name" type="number" size="small" :invalid="!!errors.user_id"  />
                </div>
                <div v-if="!(validationErrors?.user_id instanceof Array)" class="text-red-400 mt-1">
                    {{ errors.user_id }}
                </div>
                <div v-else class="mt-1">
                    <div v-for="message in validationErrors?.user_id" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="user-surname1">Points:</label>
                    <InputText v-model="leaderboard.points" id="user-surname1" type="number" size="small" :invalid="!!errors.points"/>
                </div>
                <div v-if="!(validationErrors?.points instanceof Array)" class="text-red-400 mt-1">
                    {{ errors.points }}
                </div>
                <div v-else class="mt-1">
                    <div v-for="message in validationErrors?.points" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="user-surname2">Wins:</label>
                    <InputText v-model="leaderboard.wins" id="user-surname2" type="number" size="small" :invalid="!!errors.wins"/>
                </div>
                <div v-if="!(validationErrors?.wins instanceof Array)" class="text-red-400 mt-1">
                    {{ errors.wins }}
                </div>
                <div v-else class="mt-1">
                    <div v-for="message in validationErrors?.wins" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="user-email">Losses:</label>
                    <InputText v-model="leaderboard.losses" id="user-email" type="number" size="small" :invalid="!!errors.losses"/>
                </div>
                <div v-if="!(validationErrors?.losses instanceof Array)" class="text-red-400 mt-1">
                    {{ errors.losses }}
                </div>
                <div v-else class="mt-1">
                    <div v-for="message in validationErrors?.losses" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="user-password">Matches:</label>
                    <InputText v-model="leaderboard.matches" id="user-password" type="number" size="small" :invalid="!!errors.matches"/>
                </div>
                <div v-if="!(validationErrors?.matches instanceof Array)" class="text-red-400 mt-1">
                    {{ errors.matches }}
                </div>
                <div v-else class="mt-1">
                    <div v-for="message in validationErrors?.matches" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div>

            <!-- <div class="mb-3">
                <div class="flex items-center gap-3">
                    <label for="user-role">Role:</label>
                    <MultiSelect
                        v-model="leaderboard.role_id"
                        :options="roles"
                        size="small"
                        display="chip"
                        optionLabel="name"
                        optionValue="id"
                        filter
                        :invalid="!!errors.role_id"
                    />
                </div>
                <div class="text-red-400 mt-1">
                    {{ errors.role_id }}
                </div>
                <div class="mt-1">
                    <div v-for="message in validationErrors?.role" class="text-red-400">
                        {{ message }}
                    </div>
                </div>
            </div> -->

            <!-- Buttons -->
            <div class="mt-4 text-right">
                <Button :disabled="isLoading" type="submit">
                    <div v-show="isLoading" class=""></div>
                    <span v-if="isLoading">Processing...</span>
                    <span v-else>Save</span>
                </Button>
            </div>
        </form>
    </Panel>
</template>
<script setup>
    import { onMounted } from "vue";
    import useLeaderboards from "@/composables/leaderboards";

    const { leaderboard, storeLeaderboard, validationErrors, isLoading, errors } = useLeaderboards();

    function submitForm() {
        storeLeaderboard(leaderboard.value)
    }

</script>
