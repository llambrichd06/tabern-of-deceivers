<template> <!-- Aquesta cosa nomes ho farem un cop acavat el projecta com a extre -->
    <dialog>
        <div><h1>Match Options</h1></div>
        <div>
            <div>
                <input type="radio" id="standard" value="standard" v-model="mode">
                <label for="standard">standard</label>
                <br>
                <input type="radio" id="custom" value="custom" v-model="mode">
                <label for="custom">custom</label>
                <br>
                <!-- <span>elegit: {{ mode }}</span> -->
            </div>
            <div>
                <label for="private"><img src="" alt=""> Private</label>
                <Checkbox v-model="private" inputId="private" name="private" value="private"/>
                <label for="">Max cards played</label>
                <InputText type="text" v-model="message" placeholder="4" name="maxCardsPlayed" value="maxCardsPlayed"/>
                <label for="">Max players</label>
                <InputText type="text" v-model="message" placeholder="6" name="maxPlayers" value="maxPlayers"/>
                <label for="">Time per turn</label>
                <InputText type="text" v-model="message" placeholder="10:00" name="timePerTurn" value="timePerTurn"/>
            </div>
        </div>
        <div>
            <Button label="Save"/>
            <Button label="Close"/>
        </div>
    </dialog>
</template>

<script setups>

import { ref } from 'vue';
    
</script>
