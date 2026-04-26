<template>
    <div class="fixed inset-x-0 top-0 z-50 transition-all duration-300 bg-[#520B93] text-white">
        <nav class="mx-auto flex w-full max-w-5xl items-center justify-between px-4 py-4">
            <!-- Left side: logo + desktop links -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <router-link to="/" class="flex items-center gap-2 shrink-0">
                    <img src="/images/logo.svg" alt="logo" class="h-10 w-auto"/>
                </router-link>

                <!-- Desktop Menu Links -->
                <div v-if="isDesktop" class="flex items-center gap-6">
                    <router-link 
                        v-for="link in navLinks" 
                        :key="link.route" 
                        :to="link.route" 
                        class="text-white hover:text-white/80 font-medium transition-colors"
                    >
                        {{ link.label }}
                    </router-link>
                </div>
            </div>

            <!-- Right side -->
            <div v-if="!isDesktop">
                <button
                    @click="visibleMobileMenu = true"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="pi pi-bars text-2xl"></i>
                </button>
            </div>

            <!-- Desktop Actions -->
            <div v-else class="flex items-center gap-3">
                <template v-if="!authStore().user?.name">
                    <router-link to="/login">
                        <Button label="Login" text size="medium" />
                    </router-link>
                    <router-link to="/register">
                        <Button label="REGISTER" severity="primary" size="small" />
                    </router-link>
                </template>

                <div v-else>
                    <button 
                        type="button" 
                        @click="toggle"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-purple-300/30 transition-colors">
                        <Avatar :image="authStore().user.avatar" shape="circle" size="small" />
                        <span class="text-sm font-medium hidden xl:inline">{{ authStore().user?.name }}</span>
                        <i class="pi pi-chevron-down text-xs"></i>
                    </button>
                    <Menu ref="menu" :model="items" popup />
                </div>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div v-if="visibleMobileMenu" class="fixed inset-0 z-50 lg:hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/60" @click="visibleMobileMenu = false"></div>
            
            <!-- Panel -->
            <div
                class="absolute right-0 top-0 h-full w-full bg-[#520B93] text-white shadow-2xl sm:w-80"
                @click.stop
            >
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-white/15 p-4">
                    <div class="flex items-center gap-2">
                        <img src="/images/logo.svg" alt="logo" class="h-8" />
                        <span class="text-lg font-bold">Menu</span>
                    </div>

                    <button
                        @click="visibleMobileMenu = false"
                        class="rounded-lg p-2 text-white transition-colors hover:bg-purple-300/20"
                    >
                        <i class="pi pi-times text-xl"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex h-[calc(100%-5rem)] flex-col gap-4 overflow-y-auto p-4">
                    <!-- Nav Links -->
                    <div class="flex flex-col gap-2">
                        <router-link
                            v-for="link in navLinks"
                            :key="link.route"
                            :to="link.route"
                            @click="visibleMobileMenu = false"
                            class="flex items-center gap-3 rounded-2xl border border-white/10 bg-purple-300/20 p-3 text-white transition-colors hover:bg-purple-300/35"
                        >
                            <i :class="link.icon"></i>
                            <span>{{ link.label }}</span>
                        </router-link>
                    </div>

                    <div class="border-t border-white/15"></div>

                    <!-- Auth -->
                    <div class="flex flex-col gap-3">
                        <template v-if="!authStore().user?.name">
                            <router-link to="/login" @click="visibleMobileMenu = false">
                                <Button label="Login" severity="secondary" class="w-full" />
                            </router-link>

                            <router-link to="/register" @click="visibleMobileMenu = false">
                                <Button label="REGISTER" severity="primary" class="w-full" />
                            </router-link>
                        </template>

                        <template v-else>
                            <div class="rounded-2xl border border-white/10 bg-purple-300/20 p-3 flex items-center gap-2">
                                <Avatar :image="authStore().user.avatar" shape="circle" size="small" />
                                <div>
                                    <div class="font-medium text-white">{{ authStore().user.name }}</div>
                                    <div class="text-xs text-white/70">{{ authStore().user.email }}</div>
                                </div>
                            </div>

                            <Button
                                label="Ir al Perfil"
                                icon="pi pi-th-large"
                                severity="secondary"
                                class="w-full"
                                @click="navigateToDashboard"
                            />

                            <Button v-if="authStore().user?.roles?.some(r => r.name.includes('admin')) || false"
                                label="Ir a Administrador"
                                icon="pi pi-wrench"
                                severity="secondary"
                                class="w-full"
                                @click="navigateToAdmin"
                            />

                            <Button
                                label="Cerrar Sesión"
                                icon="pi pi-power-off"
                                severity="danger"
                                class="w-full"
                                @click="handleLogout"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Spacer -->
    <div class="h-20 bg-[#520B93]"></div>
</template>

<script setup>
import { useLayout } from "@/composables/layout.js";
import useAuth from "@/composables/auth";
import { authStore } from "../store/auth";
import LocaleSwitcher from "../components/LocaleSwitcher.vue";
import { ref, computed, onBeforeMount, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const menu = ref();
const visibleMobileMenu = ref(false);
const isScrolled = ref(false);
const isDesktop = ref(window.innerWidth >= 992);

const { processing, logout } = useAuth();
const { toggleDarkMode, isDarkTheme, setDefaultMode } = useLayout();

const navLinks = [
    { label: 'Home', route: '/', icon: 'pi pi-home' },
    { label: 'Rooms', route: '/rooms' },
    { label: 'Leaderboard', route: '/leaderboard'}
];

const items = computed(() => [
    {
        items: [
            { label: 'Profile', icon: 'pi pi-user', command: () => router.push('/app/profile') },
            { 
                label: 'Admin Panel', 
                icon: 'pi pi-cog', 
                command: () => router.push('/admin'),
                visible: authStore().user?.roles?.some(r => r.name.includes('admin')) || false
            },
            // { label: 'Mi Panel', icon: 'pi pi-th-large', route: '/app' },
            { separator: true },
            {
                label: 'Log off',
                icon: 'pi pi-power-off',
                class: 'text-red-500',
                command: () => {
                    handleLogout()
                }
            }
        ]
    }
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

const navigateToDashboard = () => {
    visibleMobileMenu.value = false;
    router.push('/app');
}

const navigateToAdmin = () => {
    visibleMobileMenu.value = false;
    router.push('/admin');
}

const handleLogout = () => {
    visibleMobileMenu.value = false;
    logout();
}

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
}

const handleResize = () => {
    isDesktop.value = window.innerWidth >= 992;
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', handleResize);
});

onBeforeMount(() => {
    setDefaultMode()
})
</script>

