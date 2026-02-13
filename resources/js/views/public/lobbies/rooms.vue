<template>
    <section>
        <div><h2>Search Game</h2></div>
        <div><h2>Host Game</h2></div>
        <div><h2>Quick Match</h2></div>
    </section>
    <section>
        <div><h1>Avalible rooms</h1></div>
        <div> <!--Filtros-->
            <div>Players</div>
            <div>Game Modes</div>
        </div>
         <div>
            <!-- fer un bucle per que crei divs semblants al seguent -->
             <div>
                <div>
                    <div>Players: [num jugadores en sala]</div>
                    <div>Mode: [mode]</div>
                </div>
                <div>
                    <div>
                        <div><img src="" alt="fotoPerfil"></div>
                        <div>UserName</div>
                    </div>
                    <div>
                        <div><img src="" alt="fotoPerfil"></div>
                        <div>UserName</div>
                    </div>
                    <div>
                        <div><img src="" alt="fotoPerfil"></div>
                        <div>UserName</div>
                    </div>
                    <div>
                        <div><img src="" alt="fotoPerfil"></div>
                        <div>UserName</div>
                    </div>
                    <div>
                        <div><img src="" alt="fotoPerfil"></div>
                        <div>UserName</div>
                    </div>
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
</script>
