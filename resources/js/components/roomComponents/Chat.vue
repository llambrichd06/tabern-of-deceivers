<template>
	<Card> 
	    <!-- para iniciar el chat poner php artisan reverb:start en cmd -->
	    <template #content>
	        <div class="flex flex-col justify-end mb-2 w-full wrap-anywhere">
	            <p v-for="message in messages">
	                {{ message }}
	            </p>
	        </div>
	        <InputText id="chat" placeholder="Write message..." v-model="currentMessage"/>
	        <Button label="Send Message" @click="sendMessage"/>
	    </template>
	</Card>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

const props = defineProps<{
	roomId: integer;
}>()
const chatLoading = ref(false);
const currentMessage = ref('')
const messages = ref([])

onMounted(async () => {
	window.Echo.join(`chat.room.${props.roomId}`)
	    .listen('MessageSent', (e) => {
        	// Standard event listener for messages within that room
        	console.log(e);
        	messages.value.push(e.message.user_name + ': ' + e.message.text )
        })
        .joining((user) => {
            // Runs when a new person joins
        	messages.value.push(`${user.name} joined the room.`)
        })
        .leaving((user) => {
            // Runs when someone closes the tab or disconnects
        	messages.value.push(`${user.name} left the room.`)
        })
})

const sendMessage = () => { //SHOULD PROBABLLY MOVE THIS TO EITHER ROOM COMPOSER OR MAKE A MESSAGE COMPONENT
    if (!chatLoading.value) {
        chatLoading.value = true;
        
        axios.post('/api/messages/sent/'+props.roomId,{text: currentMessage.value})
        .then(response => {
            console.log(response);
        }).catch(error =>{
            console.log(error);
        }).finally(
            chatLoading.value = false,
            currentMessage.value = ''
        )
    }
}
</script>
