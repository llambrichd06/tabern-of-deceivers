<template>
    <div class="min-h-screen bg-[#520B93] text-white">
        <section class="px-4 pt-2 pb-10 md:pt-1 md:pb-14">
            <div class="mx-auto w-full max-w-5xl">
                <div class="flex min-h-[80vh] flex-col items-center justify-center">
                    <!-- Title -->
                    <div class="mb-8 w-full max-w-3xl text-center">
                        <h1 class="mb-6 text-4xl font-extrabold tracking-tight md:text-6xl">
                            Welcome to the Tavern of Deceivers!
                        </h1>

                        <p class="mx-auto max-w-3xl text-base leading-7 text-white/90 md:text-lg">
                            Log in to continue
                        </p>
                    </div>

                    <!-- Form card -->
                    <div class="w-full max-w-2xl rounded-3xl bg-purple-300/35 p-6 shadow-[0_15px_20px_rgba(0,0,0,0.28)] md:p-8 lg:p-10">
                        <form @submit.prevent="submitLogin" class="space-y-6">
                            <!-- Email -->
                            <div class="flex flex-col gap-2">
                                <label for="email" class="font-medium text-white">
                                    {{ $t('email') }}
                                </label>
                                <InputText
                                    id="email"
                                    type="email"
                                    v-model="loginForm.email"
                                    placeholder="your@email.com"
                                    :class="['auth-input', { 'p-invalid': validationErrors?.email }]"
                                />
                                <small v-if="validationErrors?.email" class="text-red-300">
                                    <div v-for="message in validationErrors.email" :key="message">
                                        {{ message }}
                                    </div>
                                </small>
                            </div>

                            <!-- Password -->
                            <div class="flex flex-col gap-2">
                                <label for="password" class="font-medium text-white">
                                    {{ $t('password') }}
                                </label>
                                <Password
                                    id="password"
                                    v-model="loginForm.password"
                                    placeholder="••••••••"
                                    :toggleMask="true"
                                    :feedback="false"
                                    inputClass="auth-input w-full"
                                    :class="{ 'p-invalid': validationErrors?.password }"
                                    fluid
                                />
                                <small v-if="validationErrors?.password" class="text-red-300">
                                    <div v-for="message in validationErrors.password" :key="message">
                                        {{ message }}
                                    </div>
                                </small>
                            </div>

                            <!-- Remember / Forgot -->
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-2">
                                    <Checkbox
                                        v-model="loginForm.remember"
                                        inputId="remember"
                                        binary
                                    />
                                    <label for="remember" class="cursor-pointer text-sm text-white/90">
                                        {{ $t('remember_me') }}
                                    </label>
                                </div>

                                <!-- <router-link
                                    :to="{ name: 'auth.forgot-password' }"
                                    class="text-sm font-medium text-white/90 transition-colors hover:text-white"
                                >
                                    {{ $t('forgot_password') }}
                                </router-link> -->
                            </div>

                            <!-- Submit -->
                            <Button
                                type="submit"
                                :label="$t('login')"
                                :loading="processing"
                                :disabled="processing"
                                class="w-full"
                                severity="primary"
                                size="large"
                            />

                            <!-- Register -->
                            <div class="text-center">
                                <p class="text-sm text-white/80">
                                    Don't have an account?
                                    <router-link
                                        :to="{ name: 'auth.register' }"
                                        class="font-medium text-white transition-colors hover:text-white/80"
                                    >
                                        Register here
                                    </router-link>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import useAuth from '@/composables/auth';

const { loginForm, validationErrors, processing, submitLogin } = useAuth();
</script>

<style scoped>
:deep(.pi) {
    font-family: 'primeicons' !important;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    display: inline-block;
}

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

:deep(.p-checkbox .p-checkbox-box) {
    background: rgba(255, 255, 255, 0.08) !important;
    border: 1px solid rgba(255, 255, 255, 0.25) !important;
}

:deep(.p-checkbox.p-highlight .p-checkbox-box) {
    background: rgba(255, 255, 255, 0.18) !important;
    border-color: rgba(255, 255, 255, 0.35) !important;
}
</style>