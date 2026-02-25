<template>
    <!-- ------------------------------ Menu Buttons ------------------------------ -->
    <section>
        <div class="">
            <Button label="Search Game" />
            <Button label="Host Game" />
            <Button label="Quick Match" />
        </div>
    </section>
    <section class="flex justify-center">
        <h1>Avalible rooms</h1>
        <!-- <div> Filtros
            <div>Players</div>
            <div>Game Modes</div>
        </div> -->
        <div>
            <!-- fer un bucle per que crei divs semblants al seguent -->
            <div>
                <div>
                    <div>Players: [num jugadores en sala]</div>
                    <!-- <div>Mode: [mode]</div> -->
                </div>
                <div>
                    <!-- We create the users with a loop. -->
                    <div>
                        <div><img src="" alt="fotoPerfil"></div>
                        <div>UserName</div>
                    </div>
                </div>
                <div>
                    <p>host: [userName]</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { authStore } from "@/store/auth";
import { ref } from "vue";

</script>
