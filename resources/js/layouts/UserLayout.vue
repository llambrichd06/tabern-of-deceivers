<template>
    <div class="bg-[#520B93] text-white">
        <MainLayout :menuItems="items" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import MainLayout from './MainLayout.vue';

const items = ref([
   
    // {
    //     label: 'Contenido',
    //     items: [
    //         {
    //             label: 'Posts',
    //             icon: 'pi pi-th-large',
    //             route: '/app/posts'
    //         },
    //     ]
    // },
    {
        label: 'Cuenta',
        items: [
            {
                label: 'Perfil',
                icon: 'pi pi-user',
                route: '/app/profile'
            }
        ]
    }
]);
</script>
