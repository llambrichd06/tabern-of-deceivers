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
            <InputText id="code" v-model="code"/>
            <label for="code">Room Code: <span class="requiredIcon">*</span></label>
        </FloatLabel>
        <!-- <FloatLabel variant="on">
            <InputText id="password" v-model="password" />
            <Message size="small" severity="secondary" variant="simple">If the room has no password, leave this empty.</Message>
            <label for="password">Password</label>
        </FloatLabel> -->
        <div class="flex justify-around gap-2">
            <Button label="Enter" @click="joinRoomWithCode" />
            <Button label="Cancel" severity="danger" @click="visible = false" />
        </div>
    </Dialog>
</template>

<script setup>
import { ref } from 'vue';
import useRooms from "@/composables/rooms.js";
import { useRouter } from 'vue-router'
import { id } from 'yup-locales';

const { joinRoomByCode } = useRooms();
const code = ref('');
const router = useRouter();
let visible = defineModel('visible');

const joinRoomWithCode = async () => {
    joinRoomByCode(code.value)
    .then(data => {
        router.push({ name: 'lobby', params: { id: data.id } });
    })
}
    
</script>

<style lang="scss" scoped>

</style>

