<template>
    <div class="w-full min-h-screen bg-[#520B93] text-white">
        <div class="w-full space-y-6">
            <!-- Avatar Section -->
            <section class="w-full">
                <div class="w-full rounded-3xl bg-purple-300/35 p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-8">
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
                            class="avatar-upload w-full mt-6"
                        />
                    </div>
                </div>
            </section>

            <!-- Personal Data Section -->
            <section class="w-full">
                <div class="w-full rounded-3xl bg-purple-300/35 p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-8">
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
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import useUsers from "@/composables/users";
import { authStore } from "@/store/auth";

const auth = authStore();
const { getUser, user } = useUsers();

onMounted(() => {
    getUser(auth.user.id);
});

const onBeforeUpload = (event) => {
    event.formData.append("id", user.value.id);
};

const onTemplatedUpload = () => {
    getUser(auth.user.id);
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