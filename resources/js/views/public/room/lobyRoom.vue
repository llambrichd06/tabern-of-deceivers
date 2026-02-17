<template>
    <section>
        <!-- Opcions de les sales -->
        <p>Room code: [RoomCode]</p>
        <p>Private</p>
        <Image src="" alt="lockRoom"/>
        <Image src="" alt="unlockRoom"/>
        <Button label="Edit Match Rules"/>
        <Button label="Leave Room"/>
        <Button label="Start Match"/>
    </section>
    <section>
        <!-- Usuaris i host -->
        <div>
            <Image src="" alt="imgPerfilHost"/>
            <p>UserName</p>
        </div>
        <p>Host</p>
        <!-- bucle per els altres jugadors que es igual que el anterior -->
    </section>
    <section>
        <!-- Descripcio i gameplay -->
        <!-- Agafarem una descripcio de una taula si ho acabem fent el seguent text es un exemplpe que s'ha de modificar-->
        <h2>Aqui anira una descripcio del game mode</h2>
    </section>
    <section>
        <!-- chat -->

    </section>
</template>

<script setup>
import { authStore } from "@/store/auth";
</script>
