<template>
    <div class="min-h-screen bg-[#520B93] text-white">
        <div class="mx-auto w-full max-w-5xl px-4">
            <div class="flex min-h-[80vh] flex-col items-center justify-center pt-20 pb-8">
                <!-- Title -->
                <div class="mb-8 w-full max-w-3xl text-center">
                    <h2 class="text-4xl font-bold md:text-6xl leading-tight">
                        {{ $t('register') }}
                    </h2>
                    <p class="mt-3 text-base text-white/80 md:text-lg">
                        Register to start
                    </p>
                </div>

                <!-- Form -->
                <div class="w-full max-w-2xl rounded-3xl bg-purple-300/35 p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-8 lg:p-10">
                    <form @submit.prevent="submitRegister" class="space-y-6">
                        <!-- Name -->
                        <div class="flex flex-col gap-2">
                            <label for="name" class="font-medium text-white">
                                {{ $t('name') }}
                            </label>
                            <InputText
                                id="name"
                                v-model="registerForm.name"
                                placeholder="Complete name"
                                :class="['auth-input', { 'p-invalid': !!validationErrors?.name }]"
                            />
                            <small v-if="validationErrors?.name" class="text-red-300">
                                {{ validationErrors.name[0] }}
                            </small>
                        </div>

                        <!-- Surnames -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <label for="surname1" class="font-medium text-white">
                                    {{ $t('surname1') }}
                                </label>
                                <InputText
                                    id="surname1"
                                    v-model="registerForm.surname1"
                                    placeholder="First surname"
                                    :class="['auth-input', { 'p-invalid': !!validationErrors?.surname1 }]"
                                />
                                <small v-if="validationErrors?.surname1" class="text-red-300">
                                    {{ validationErrors.surname1[0] }}
                                </small>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="surname2" class="font-medium text-white">
                                    {{ $t('surname2') }}
                                </label>
                                <InputText
                                    id="surname2"
                                    v-model="registerForm.surname2"
                                    placeholder="Second surname"
                                    :class="['auth-input', { 'p-invalid': !!validationErrors?.surname2 }]"
                                />
                                <small v-if="validationErrors?.surname2" class="text-red-300">
                                    {{ validationErrors.surname2[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label for="email" class="font-medium text-white">
                                {{ $t('email') }}
                            </label>
                            <InputText
                                id="email"
                                type="email"
                                v-model="registerForm.email"
                                placeholder="your@email.com"
                                :class="['auth-input', { 'p-invalid': !!validationErrors?.email }]"
                            />
                            <small v-if="validationErrors?.email" class="text-red-300">
                                {{ validationErrors.email[0] }}
                            </small>
                        </div>

                        <!-- Passwords -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <label for="password" class="font-medium text-white">
                                    {{ $t('password') }}
                                </label>
                                <Password
                                    id="password"
                                    v-model="registerForm.password"
                                    placeholder="••••••••"
                                    toggleMask
                                    :feedback="false"
                                    inputClass="auth-input w-full"
                                    :class="{ 'p-invalid': !!validationErrors?.password }"
                                    fluid
                                />
                                <small v-if="validationErrors?.password" class="text-red-300">
                                    {{ validationErrors.password[0] }}
                                </small>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="password_confirmation" class="font-medium text-white">
                                    {{ $t('confirm_password') }}
                                </label>
                                <Password
                                    id="password_confirmation"
                                    v-model="registerForm.password_confirmation"
                                    placeholder="••••••••"
                                    toggleMask
                                    :feedback="false"
                                    inputClass="auth-input w-full"
                                    :class="{ 'p-invalid': !!validationErrors?.password_confirmation }"
                                    fluid
                                />
                                <small v-if="validationErrors?.password_confirmation" class="text-red-300">
                                    {{ validationErrors.password_confirmation[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Submit -->
                        <Button
                            type="submit"
                            :label="$t('register')"
                            :loading="processing"
                            :disabled="processing"
                            class="auth-submit-btn w-full rounded-2xl! border-0! py-3! text-lg! font-semibold! shadow-[0_12px_18px_rgba(0,0,0,0.28)]"
                            size="large"
                        />

                        <!-- Login link -->
                        <div class="text-center">
                            <p class="text-sm text-white/80">
                                Already have an account?
                                <router-link
                                    :to="{ name: 'auth.login' }"
                                    class="font-medium text-white transition-colors hover:text-white/80"
                                >
                                    Log in here
                                </router-link>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import useAuth from '@/composables/auth';

const { registerForm, validationErrors, processing, submitRegister } = useAuth();
</script>

<style scoped>
:deep(.p-inputtext),
:deep(.p-password),
:deep(.p-password-input) {
    width: 100%;
}

:deep(.auth-input),
:deep(.auth-input.p-inputtext),
:deep(.p-password .auth-input),
:deep(.p-password-input) {
    width: 100%;
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    border-radius: 1rem !important;
    box-shadow: none !important;
}

:deep(.auth-input::placeholder),
:deep(.p-password-input::placeholder) {
    color: rgba(255, 255, 255, 0.6) !important;
}

:deep(.auth-input:enabled:focus),
:deep(.p-password-input:enabled:focus),
:deep(.p-inputtext:enabled:focus) {
    border-color: rgba(255, 255, 255, 0.35) !important;
    box-shadow: 0 0 0 0.15rem rgba(255, 255, 255, 0.08) !important;
}

:deep(.auth-submit-btn.p-button) {
    background: linear-gradient(90deg, #8dd0ee 0%, #2fd3e6 100%) !important;
    color: white !important;
}

:deep(.auth-submit-btn.p-button:enabled:hover) {
    filter: brightness(1.05);
}
</style>