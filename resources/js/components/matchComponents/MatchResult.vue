<template>
    <dialog>
        <h1></h1>
        <div>
            <div> <!-- Only putting one of each value because we'll loop it later somehow. -->
                <h2>Position</h2>
                <div>
                    <p v-for="i in 6">
                        {{ n }}
                    </p>
                </div>
            </div>
            <div>
                <h2>Players</h2>
                <div>
                    <Image src="" alt="Profile Picture"/>
                    <p>userName</p>
                </div>
            </div>
            <div>
                <h2>Cards left</h2>
                <p></p>
            </div>
        </div>
    </dialog>
</template>

<script setup>
import { ref } from 'vue';

</script>

<style lang="scss" scoped>

</style>