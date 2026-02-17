<template>
    <dialog>
        <h1></h1>
        <div>
            <form action="">
                <FloatLabel variant="on"> 
                    <InputText id="code" v-model="code" />
                    <label for="code">Room Code: <span class="requiredIcon">*</span></label>
                </FloatLabel>
                <FloatLabel variant="on">
                    <InputText id="password" v-model="password" />
                    <Message size="small" severity="secondary" variant="simple">If the room has no password, leave this empty.</Message>
                    <label for="password">Password</label>
                </FloatLabel>
                <Button label="Enter"/>
                <Button label="Close"/>
            </form>
        </div>

    </dialog>
</template>

<script>
import { ref } from 'vue';

    
</script>

<style lang="scss" scoped>

</style>

