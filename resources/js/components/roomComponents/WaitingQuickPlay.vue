<template>
    <Dialog 
        :visible="visible"  modal  header="Waiting quick play"  :style="{ width: '25rem' }" :closable="false">
        <div>
            <p>Searching... </p>
            <p><b>CURRENTLY NOT IMPLEMENTED</b></p>
        </div>
        <div>
            <Image src="" alt=""/>
        </div>
        <div>
            <Button label="Cancel Search" @click="visible = false"/>
        </div>
    </Dialog>
</template>

<script setup>
const visible = defineModel('visible')

</script>