<link rel="preconnect" href="https://fonts.googleapis.com"></link>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin></link>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"></link>

<!-- <link rel="preconnect" href="https://fonts.googleapis.com"></link>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin></link>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&family=Jaro:opsz@6..72&display=swap" rel="stylesheet"></link> -->

<script setup>
    import { onMounted } from 'vue'
    import useAuth from './composables/auth';

    onMounted(() => {
    useAuth().getUserSignIn()
})
</script>

<template>
    <Toast position="bottom-right" />
    <router-view />
</template>
