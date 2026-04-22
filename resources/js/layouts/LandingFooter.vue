<template>
    <footer class="border-t border-[#520B93]/15 bg-white text-[#520B93]">
        <div class="mx-auto flex w-full max-w-5xl flex-col justify-between gap-6 px-4 py-8 md:flex-row md:items-center">
            <div class="flex flex-wrap items-center justify-center gap-6 md:justify-start">
                <router-link to="/" class="font-medium transition-colors hover:text-[#520B93]/70">Home</router-link>
                <router-link to="/rooms" class="font-medium transition-colors hover:text-[#520B93]/70">Play</router-link>
                <router-link to="/leaderboard" class="font-medium transition-colors hover:text-[#520B93]/70">Leaderboard</router-link>
                <router-link to="/rules" class="font-medium transition-colors hover:text-[#520B93]/70">Rules</router-link>
            </div>

            <div class="flex justify-center md:justify-end">
                <router-link to="/contactUs" class="font-medium transition-colors hover:text-[#520B93]/70">
                    Contact Us
                </router-link>
            </div>
        </div>
    </footer>
</template>