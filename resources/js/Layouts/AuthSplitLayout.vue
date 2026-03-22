<script setup>
import { computed } from 'vue';
import { loginHeroImageUrl } from '@/config/authUi.js';

const props = defineProps({
    heroTitle: {
        type: String,
        default: 'A curadoria da sua biblioteca',
    },
    /** Se vazio, usa o texto padrão com o nome da app */
    heroParagraph: {
        type: String,
        default: '',
    },
});

const appName = import.meta.env.VITE_APP_NAME || 'Sistema Bookstore';

const heroBlurb = computed(() => {
    if (props.heroParagraph) {
        return props.heroParagraph;
    }
    return `${appName} — gestão inteligente para livrarias e acervos que exigem precisão.`;
});
</script>

<template>
    <div
        class="flex min-h-screen bg-folio-surface font-sans text-folio-on-surface"
    >
        <aside
            class="relative hidden overflow-hidden lg:block lg:w-1/2"
        >
            <img
                :src="loginHeroImageUrl"
                alt=""
                class="absolute inset-0 h-full w-full object-cover"
            />
            <div
                class="absolute inset-0 bg-gradient-to-t from-folio-primary/60 to-transparent"
            />
            <div
                class="absolute bottom-12 left-12 right-12 text-folio-on-primary"
            >
                <h2
                    class="mb-4 font-headline text-3xl font-extrabold tracking-tight text-white lg:text-4xl"
                >
                    {{ heroTitle }}
                </h2>
                <p class="max-w-md text-lg opacity-90">
                    {{ heroBlurb }}
                </p>
            </div>
        </aside>

        <main
            class="flex w-full items-center justify-center p-6 sm:p-12 lg:w-1/2"
        >
            <div class="w-full max-w-[440px]">
                <div class="mb-10">
                    <div
                        class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-folio-primary shadow-sm"
                    >
                        <span
                            class="material-symbols-outlined text-2xl text-folio-on-primary"
                            >menu_book</span
                        >
                    </div>
                    <h1
                        class="mb-2 font-headline text-3xl font-extrabold tracking-tight text-folio-primary"
                    >
                        {{ appName }}
                    </h1>
                    <p
                        class="text-sm font-medium uppercase tracking-wide text-folio-secondary"
                    >
                        Portal de acesso administrativo
                    </p>
                </div>

                <slot />

                <div
                    class="mt-12 flex flex-wrap justify-center gap-x-8 gap-y-2 text-xs font-bold uppercase tracking-widest text-folio-outline"
                >
                    <a
                        href="#"
                        class="transition-colors hover:text-folio-primary"
                        >Suporte</a
                    >
                    <a
                        href="#"
                        class="transition-colors hover:text-folio-primary"
                        >Privacidade</a
                    >
                    <a
                        href="#"
                        class="transition-colors hover:text-folio-primary"
                        >Status</a
                    >
                </div>
            </div>
        </main>
    </div>
</template>
