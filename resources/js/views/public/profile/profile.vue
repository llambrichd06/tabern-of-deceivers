<template>
    <div class="min-h-screen bg-[#520B93] text-white">
        <section class="px-4 pt-2 pb-10 md:pt-1 md:pb-14">
            <div class="mx-auto max-w-5xl space-y-6">
                <!-- Profile Top Section -->
                <section class="grid grid-cols-1 gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
                    <!-- Avatar Section -->
                    <div class="rounded-3xl bg-purple-300/35 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.3)] md:p-8">
                        <h2 class="mb-6 text-2xl font-bold text-white">Avatar</h2>

                        <div class="flex flex-col items-center">
                            <div class="mt-3 flex w-full justify-center">
                                <Avatar
                                    :image="user.avatar || 'https://bootdey.com/img/Content/avatar/avatar7.png'"
                                    class="h-32! w-32!"
                                    shape="circle"
                                />
                            </div>

                            <FileUpload
                                name="picture"
                                url="/api/users/updateimg"
                                @before-upload="onBeforeUpload"
                                @upload="onTemplatedUpload"
                                accept="image/*"
                                :maxFileSize="1500000"
                                @select="onSelectedFiles"
                                mode="basic"
                                :auto="true"
                                chooseLabel="Cambiar Avatar"
                                severity="primary"
                                class="avatar-upload mt-6 w-full"
                            />
                        </div>
                    </div>

                    <!-- Personal Data Section -->
                    <div class="rounded-3xl bg-purple-300/35 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.3)] md:p-8">
                        <h2 class="mb-6 text-2xl font-bold text-white">Datos Personales</h2>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block font-bold text-white">Name</label>
                                <div class="rounded-2xl border border-white/20 bg-white/10 p-3 text-white">
                                    {{ user.name || "-" }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block font-bold text-white">Email</label>
                                <div class="rounded-2xl border border-white/20 bg-white/10 p-3 text-white">
                                    {{ user.email || "-" }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block font-bold text-white">First Surname</label>
                                <div class="rounded-2xl border border-white/20 bg-white/10 p-3 text-white">
                                    {{ user.surname1 || "-" }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block font-bold text-white">Second Surname</label>
                                <div class="rounded-2xl border border-white/20 bg-white/10 p-3 text-white">
                                    {{ user.surname2 || "-" }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Extra Data Section -->
                <section>
                    <div class="rounded-3xl bg-purple-300/35 p-4 shadow-[0_15px_20px_rgba(0,0,0,0.3)] md:p-8">
                        <h2 class="mb-6 text-2xl font-bold text-white">Game data</h2>
                        <div>
                            <p class="mb-2 block font-bold text-white">Wins</p>
                            <p class="mb-2 block font-bold text-white">Wins</p>
                            <p class="mb-2 block font-bold text-white">Wins</p>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import useUsers from "@/composables/users";
import { authStore } from "@/store/auth";
import useLeaderboards from "../../../composables/leaderboards";

const auth = authStore();
const { getUser, user } = useUsers();
const { getMyLeaderboard, leaderboard } = useLeaderboards();

onMounted(() => {
    getUser(auth.user.id);
    
});

const onBeforeUpload = (event) => {
    event.formData.append("id", user.value.id);
};

const onTemplatedUpload = () => {
    try {
        getUser(auth.user.id);
        getMyLeaderboard();
        console.log(leaderboard.value)
    } catch (error) {
        
    }
    
};

const onSelectedFiles = () => {
    // Lógica adicional si es necesaria
};
</script>

<style scoped>
:deep(.p-fileupload-basic) {
    width: 100%;
}

:deep(.avatar-upload .p-button),
:deep(.p-fileupload-basic .p-button) {
    width: 100%;
    border-radius: 1rem !important;
    box-shadow: 0 12px 18px rgba(0, 0, 0, 0.28) !important;
}
</style>