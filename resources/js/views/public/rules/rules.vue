<template>
  <section class="min-h-screen bg-gray-50 py-16">
    <div class="mx-auto max-w-7xl px-4">
      <div class="relative">
        <!-- center line -->
        <div class="absolute left-1/2 top-0 bottom-22.5 hidden w-1 -translate-x-1/2 bg-black lg:block"></div>

        <div class="space-y-14">
          <!-- STEP 1 -->
          <div class="hidden lg:grid grid-cols-[1fr_auto_1fr] items-center gap-10">
            <div class="flex justify-end">
              <div class="relative w-full max-w-105">
                <div class="absolute -right-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-45 border-4 border-black bg-white p-8">
                  <h3 class="text-xl font-semibold">Step 1</h3>
                  <p class="mt-3 text-sm leading-6 text-gray-600">
                    First, an equal number of cards is given to each player. If the cards cannot
                    be divided equally, the remaining cards are placed in the pile.
                  </p>
                </div>
              </div>
            </div>

            <div class="relative z-10 flex items-center justify-center">
              <div class="h-6 w-6 rounded-full bg-black"></div>
            </div>

            <div class="flex justify-start">
              <div class="relative w-full max-w-90">
                <div class="absolute -left-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-45 rounded-3xl border-4 border-black bg-white p-6">
                  <div class="flex min-h-32.5 items-center justify-center">
                    <img src="https://via.placeholder.com/280x160" alt="Step 1 image" class="max-h-40 object-contain" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 2 -->
          <div class="hidden lg:grid grid-cols-[1fr_auto_1fr] items-center gap-10">
            <div class="flex justify-end">
              <div class="relative w-full max-w-105">
                <div class="absolute -right-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-45 border-4 border-black bg-white p-8">
                  <h3 class="text-xl font-semibold">Step 2</h3>
                  <p class="mt-3 text-sm leading-6 text-gray-600">
                    Then, a random player is chosen to start the game. That player chooses one
                    rank to be the pile rank and plays between 1 and 6 cards onto the pile. The
                    cards may or may not actually be of that rank.
                  </p>
                </div>
              </div>
            </div>

            <div class="relative z-10 flex items-center justify-center">
              <div class="h-6 w-6 rounded-full bg-black"></div>
            </div>

            <div class="flex justify-start">
              <div class="relative w-full max-w-95">
                <div class="absolute -left-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-50 rounded-3xl border-4 border-black bg-white p-6">
                  <div class="flex min-h-37.5 items-center justify-center">
                    <img src="https://via.placeholder.com/300x180" alt="Step 2 image" class="max-h-44 object-contain" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 3 -->
          <div class="hidden lg:grid grid-cols-[1fr_auto_1fr] items-center gap-10">
            <div class="flex justify-end">
              <div class="relative w-full max-w-105">
                <div class="absolute -right-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-45 border-4 border-black bg-white p-8">
                  <h3 class="text-xl font-semibold">Step 3</h3>
                  <p class="mt-3 text-sm leading-6 text-gray-600">
                    After that, the next player on the right can choose to play cards, and the
                    round continues in the same way.
                  </p>
                </div>
              </div>
            </div>

            <div class="relative z-10 flex items-center justify-center">
              <div class="h-6 w-6 rounded-full bg-black"></div>
            </div>

            <div class="flex justify-start">
              <div class="relative w-full max-w-90">
                <div class="absolute -left-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-47.5 rounded-3xl border-4 border-black bg-white p-6">
                  <div class="flex min-h-35 items-center justify-center">
                    <img src="https://via.placeholder.com/280x170" alt="Step 3 image" class="max-h-40 object-contain" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 4 with 2 options -->
          <div class="hidden lg:grid grid-cols-[1fr_auto_1fr] items-center gap-10">
            <div class="flex justify-end">
              <div class="relative w-full max-w-105">
                <div class="absolute -right-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-45 border-4 border-black bg-white p-8">
                  <h3 class="text-xl font-semibold">Step 4</h3>
                  <p class="mt-3 text-sm leading-6 text-gray-600">
                    After each turn, any player except the one who played last can press the Lie
                    button at the bottom right. Then the last played cards are revealed, which
                    leads to one of two possible outcomes.
                  </p>
                  <p class="mt-3 text-sm leading-6 text-gray-600">
                    If all the cards played by the last player are of the current pile rank, or
                    are jokers, then that player was telling the truth. In that case, the player
                    who called Lie takes all the cards in the pile, and the truthful player
                    starts the round.
                  </p>
                  <p class="mt-3 text-sm leading-6 text-gray-600">
                    If any of the cards played by the last player are not of the current pile
                    rank and are not jokers, then that player was lying. In that case, the lying
                    player takes all the cards in the pile, and the player who discovered the lie
                    starts the next round.
                  </p>
                </div>
              </div>
            </div>

            <div class="relative z-10 flex items-center justify-center">
              <div class="h-6 w-6 rounded-full bg-black"></div>
            </div>

            <div class="flex justify-start">
              <div class="relative w-full max-w-90">
                <div class="absolute -left-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>

                <div class="min-h-45 rounded-3xl border-4 border-black bg-white p-4">
                  <div class="flex min-h-35 flex-col justify-between gap-3">
                    <div class="rounded-2xl border-2 border-black bg-white p-3">
                      <p class="mb-2 text-xs font-medium text-gray-500">IMG OP 1</p>
                      <div class="flex min-h-10.5 items-center justify-center">
                        <img
                          src="https://via.placeholder.com/180x70"
                          alt="Option 1 image"
                          class="max-h-14 object-contain"
                        />
                      </div>
                    </div>

                    <div class="rounded-2xl border-2 border-black bg-white p-3">
                      <p class="mb-2 text-xs font-medium text-gray-500">IMG OP 2</p>
                      <div class="flex min-h-10.5 items-center justify-center">
                        <img
                          src="https://via.placeholder.com/180x70"
                          alt="Option 2 image"
                          class="max-h-14 object-contain"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 5 -->
          <div class="hidden lg:grid grid-cols-[1fr_auto_1fr] items-center gap-10">
            <div class="flex justify-end">
              <div class="relative w-full max-w-105">
                <div class="absolute -right-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-45 border-4 border-black bg-white p-8">
                  <h3 class="text-xl font-semibold">Step 5</h3>
                  <p class="mt-3 text-sm leading-6 text-gray-600">
                    A player wins when they finish their turn with 0 cards and the next player's
                    turn ends without him getting any cards.
                  </p>
                </div>
              </div>
            </div>

            <div class="relative z-10 flex items-center justify-center">
              <div class="h-6 w-6 rounded-full bg-black"></div>
            </div>

            <div class="flex justify-start">
              <div class="relative w-full max-w-90">
                <div class="absolute -left-10 top-1/2 h-1 w-10 -translate-y-1/2 bg-black"></div>
                <div class="min-h-45 rounded-3xl border-4 border-black bg-white p-6">
                  <div class="flex min-h-32.5 items-center justify-center">
                    <img src="https://via.placeholder.com/280x160" alt="Step 5 image" class="max-h-40 object-contain" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- MOBILE -->
          <div class="space-y-6 lg:hidden">
            <div class="border-4 border-black bg-white p-6">
              <h3 class="text-xl font-semibold">Text first step</h3>
              <p class="mt-3 text-sm text-gray-600">Add the first explanation here.</p>
            </div>

            <div class="rounded-3xl border-4 border-black bg-white p-6">
              <div class="flex min-h-35 items-center justify-center">
                <img src="https://via.placeholder.com/280x160" alt="Step 1 image" class="max-h-40 object-contain" />
              </div>
            </div>

            <div class="border-4 border-black bg-white p-6">
              <h3 class="text-xl font-semibold">Text second step</h3>
              <p class="mt-3 text-sm text-gray-600">Add the second step text here.</p>
            </div>

            <div class="rounded-3xl border-4 border-black bg-white p-6">
              <div class="flex min-h-35 items-center justify-center">
                <img src="https://via.placeholder.com/280x160" alt="Step 2 image" class="max-h-40 object-contain" />
              </div>
            </div>

            <div class="border-4 border-black bg-white p-6">
              <h3 class="text-xl font-semibold">Text third step</h3>
              <p class="mt-3 text-sm text-gray-600">Add the third step text here.</p>
            </div>

            <div class="rounded-3xl border-4 border-black bg-white p-6">
              <div class="flex min-h-35 items-center justify-center">
                <img src="https://via.placeholder.com/280x160" alt="Step 3 image" class="max-h-40 object-contain" />
              </div>
            </div>

            <div class="border-4 border-black bg-white p-6">
              <h3 class="text-xl font-semibold">Text fourth step</h3>
              <p class="mt-3 text-sm text-gray-600">Option 1 and option 2 can be explained here.</p>
            </div>

            <div class="rounded-3xl border-4 border-black bg-white p-6">
              <p class="mb-4 text-sm font-medium text-gray-500">IMG OP 1</p>
              <div class="flex min-h-30 items-center justify-center">
                <img src="https://via.placeholder.com/260x140" alt="Option 1 image" class="max-h-36 object-contain" />
              </div>
            </div>

            <div class="rounded-3xl border-4 border-black bg-white p-6">
              <p class="mb-4 text-sm font-medium text-gray-500">IMG OP 2</p>
              <div class="flex min-h-30 items-center justify-center">
                <img src="https://via.placeholder.com/260x140" alt="Option 2 image" class="max-h-36 object-contain" />
              </div>
            </div>

            <div class="border-4 border-black bg-white p-6">
              <h3 class="text-xl font-semibold">Fifth step, how to win game</h3>
              <p class="mt-3 text-sm text-gray-600">Add the final explanation here.</p>
            </div>

            <div class="rounded-3xl border-4 border-black bg-white p-6">
              <div class="flex min-h-35 items-center justify-center">
                <img src="https://via.placeholder.com/280x160" alt="Step 5 image" class="max-h-40 object-contain" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { ref, onMounted } from "vue";
onMounted(async () => {
    loading.value = true;
    try {
    } catch (error) {
    }finally {
        loading.value = false;
    }
});

</script>
