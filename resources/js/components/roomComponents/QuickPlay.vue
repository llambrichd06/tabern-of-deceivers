<template> <!-- Aquesta cosa nomes ho farem un cop acavat el projecta com a extre -->
    <dialog>
        <div>
            <p>Filter modes</p>
        </div>
        <div>
            <label for="modeStandar"> Standar </label>
            <Checkbox v-model="standar" inputId="modeStandar" name="standar" value="standar"/>
            <label for="modeCustom"> Custom </label>
            <Checkbox v-model="custom" inputId="modeCustom" name="custom" value="custom"/>
        </div>
        <div>
            <Button label="Search"/>
            <Button label="Close"/>
        </div>
    </dialog>
</template>

<script setups>

import { ref } from 'vue';
    
</script>
