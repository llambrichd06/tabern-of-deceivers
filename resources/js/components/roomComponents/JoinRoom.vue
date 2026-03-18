<template>
    <!-- <dialog>
        <h1></h1>
        <div>
            <form action="">


                <Button label="Enter"/>
                <Button label="Close"/>
            </form>
        </div>

    </dialog> -->
    <Dialog :visible="visible" modal  :style="{ width: '25rem' }">
        <template #header>
            <h2 class="flex justify-center">Join room</h2>
        </template>
        <FloatLabel variant="on"> 
            <InputText id="code" v-model="code" />
            <label for="code">Room Code: <span class="requiredIcon">*</span></label>
        </FloatLabel>
        <!-- <FloatLabel variant="on">
            <InputText id="password" v-model="password" />
            <Message size="small" severity="secondary" variant="simple">If the room has no password, leave this empty.</Message>
            <label for="password">Password</label>
        </FloatLabel> -->
        <div class="flex justify-around gap-2">
            <Button label="Enter" @click="visible = false" />
            <Button label="Cancel" severity="danger" @click="visible = false" />
        </div>
    </Dialog>
</template>

<script setup>
import { ref } from 'vue';
// import { defineProps } from 'vue';

const visible = defineModel('visible');
// const emit = defineEmits()

    
</script>

<style lang="scss" scoped>

</style>

