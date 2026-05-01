<template>
    <div class="page-content min-h-screen overflow-x-hidden bg-[#520B93] text-white">
        <section class="px-4 pt-2 pb-10 md:pt-1 md:pb-14">
            <div class="mx-auto max-w-5xl text-center">
                <h1 class="mb-6 text-4xl font-extrabold tracking-tight md:text-6xl">
                    Contact Us
                </h1>

                <p class="mx-auto max-w-3xl text-base leading-7 text-white/90 md:text-lg">
                    Have a question, found a bug, or want to share feedback?
                    We would love to hear from you.
                </p>
            </div>
        </section>

        <!-- CONTACT INFO + FORM -->
        <section class="mt-6 px-6 py-8 md:px-10 md:py-10">
            <div class="mx-auto grid max-w-5xl gap-8 md:grid-cols-[1fr_1.3fr]">
                <!-- CONTACT INFO -->
                <div class="rounded-3xl bg-purple-300/35 p-8 shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
                    <h2 class="mb-6 text-3xl font-bold">
                        Get in touch
                    </h2>

                    <p class="mb-8 text-base leading-7 text-white/95 md:text-lg">
                        You can contact us for support, account issues, technical problems,
                        partnerships, or general questions about the game.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-black/20">
                                <i class="pi pi-envelope text-xl text-white"></i>
                            </div>
                            <div>
                                <p class="text-xl font-semibold">Email</p>
                                <p class="text-base leading-7 text-white/90 md:text-lg">support@tabernofdeceivers.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-black/20">
                                <i class="pi pi-discord text-xl text-white"></i>
                            </div>
                            <div>
                                <p class="text-xl font-semibold">Community</p>
                                <p class="text-base leading-7 text-white/90 md:text-lg">Join our Discord!</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-black/20">
                                <i class="pi pi-clock text-xl text-white"></i>
                            </div>
                            <div>
                                <p class="text-xl font-semibold">Response time</p>
                                <p class="text-base leading-7 text-white/90 md:text-lg">We usually reply within 24–48 hours.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM -->
                <div class="rounded-3xl bg-purple-300/35 p-8 shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
                    <h2 class="mb-6 text-3xl font-bold">
                        Send us a message
                    </h2>

                    <form class="flex flex-col gap-5" @submit.prevent="handleSubmit">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/90">
                                Name
                            </label>
                            <InputText
                                v-model="form.name"
                                placeholder="Your name"
                                class="w-full"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/90">
                                Email
                            </label>
                            <InputText
                                v-model="form.email"
                                type="email"
                                placeholder="you@example.com"
                                class="w-full"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/90">
                                Subject
                            </label>
                            <InputText
                                v-model="form.subject"
                                placeholder="What do you need help with?"
                                class="w-full"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/90">
                                Message
                            </label>
                            <Textarea
                                v-model="form.message"
                                rows="6"
                                autoResize
                                placeholder="Write your message here..."
                                class="w-full"
                            />
                        </div>

                        <div class="pt-2">
                            <Button
                                type="submit"
                                label="Send Message"
                                severity="primary"
                                :loading="sending"
                                class="w-full rounded-2xl! px-10! py-4! text-lg! font-semibold! shadow-[0_15px_20px_rgba(0,0,0,0.3)]"
                            />
                        </div>

                        <p v-if="successMessage" class="text-base font-medium leading-7 text-green-200 md:text-lg">
                            {{ successMessage }}
                        </p>
                    </form>
                </div>
            </div>
        </section>

        <!-- HELP / FAQ -->
        <section class="mt-6 px-4 pb-14">
            <div class="mx-auto max-w-5xl rounded-2xl bg-purple-300/35 px-6 py-8 shadow-[0_15px_20px_rgba(0,0,0,0.3)] md:px-10 md:py-10">
                <h2 class="mb-8 text-center text-4xl font-bold">
                    Before you contact us
                </h2>

                <p class="mx-auto mb-8 max-w-3xl text-center text-base leading-7 text-white/90 md:text-lg">
                    You may find a quick answer in our help sections before sending a message.
                </p>

                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-3xl bg-black/15 p-8 text-center shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
                        <i class="pi pi-book mb-4 text-4xl text-white/90"></i>
                        <p class="mb-2 text-xl font-semibold">Game Rules</p>
                        <p class="text-base leading-7 opacity-90 md:text-lg">
                            Learn the basics, understand the mechanics, and get ready to deceive.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-black/15 p-8 text-center shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
                        <i class="pi pi-question-circle mb-4 text-4xl text-white/90"></i>
                        <p class="mb-2 text-xl font-semibold">Common Questions</p>
                        <p class="text-base leading-7 opacity-90 md:text-lg">
                            Check answers for login issues, rooms, rankings, and account questions.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-black/15 p-8 text-center shadow-[0_15px_20px_rgba(0,0,0,0.2)]">
                        <i class="pi pi-bug mb-4 text-4xl text-white/90"></i>
                        <p class="mb-2 text-xl font-semibold">Bug Reports</p>
                        <p class="text-base leading-7 opacity-90 md:text-lg">
                            Found something broken? Send us the details and we will investigate it.
                        </p>
                    </div>
                </div>

                <div class="mt-10 flex justify-center">
                    <Button
                        label="View Rules"
                        as="router-link"
                        to="/rules"
                        severity="secondary"
                        class="rounded-2xl! px-8! py-4! text-xl! font-semibold! shadow-[0_15px_20px_rgba(0,0,0,0.3)] md:px-16!"
                    />
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref } from "vue";

const sending = ref(false);
const successMessage = ref("");

const form = ref({
    name: "",
    email: "",
    subject: "",
    message: ""
});

const handleSubmit = async () => {
    sending.value = true;
    successMessage.value = "";

    try {
        // Aquí puedes conectar tu endpoint / API
        // await axios.post("/api/contact", form.value);

        await new Promise((resolve) => setTimeout(resolve, 800));

        successMessage.value = "Your message has been sent successfully!";
        form.value = {
            name: "",
            email: "",
            subject: "",
            message: ""
        };
    } catch (error) {
        console.error("Failed to send message:", error);
    } finally {
        sending.value = false;
    }
};
</script>

<style scoped></style>