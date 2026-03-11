<template>
    <Dialog 
        :visible="visible"  modal  header="Waiting quick play"  :style="{ width: '25rem' }" :closable="false">
        <div>
            <p>Searching...</p>
        </div>
        <div>
            <Image src="" alt=""/>
        </div>
        <div>
            <Button label="Cancel Search" @click="closeDialog"/>
        </div>
    </Dialog>
</template>

<script setup>
const props = defineProps({
    visible: Boolean
})

const emit = defineEmits(['update:visible']);

const closeDialog = () => {
    emit('update:visible', false);
}
</script>