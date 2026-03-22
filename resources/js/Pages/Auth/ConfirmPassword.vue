<script setup>
import AuthSplitLayout from '@/Layouts/AuthSplitLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    password: '',
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Confirmar sessão" />

    <AuthSplitLayout
        hero-title="Área protegida"
        hero-paragraph="Por segurança, confirme a sua identidade antes de continuar nesta sessão."
    >
        <div
            class="overflow-hidden rounded-2xl border border-folio-outline-variant/10 bg-white shadow-folio"
        >
            <div
                class="border-b border-folio-outline-variant/10 bg-folio-surface-low px-8 py-5"
            >
                <h2
                    class="font-headline text-lg font-bold text-folio-on-surface"
                >
                    Confirmar senha
                </h2>
            </div>

            <form class="space-y-6 p-8" @submit.prevent="submit">
                <p class="text-sm leading-relaxed text-folio-secondary">
                    Esta é uma zona sensível da aplicação. Introduza a sua
                    senha atual para prosseguir.
                </p>

                <div class="space-y-2">
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                        for="password"
                        >Senha</label
                    >
                    <div class="group relative">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                        >
                            <span
                                class="material-symbols-outlined text-folio-outline-variant transition-colors group-focus-within:text-folio-primary"
                                >lock</span
                            >
                        </div>
                        <input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            required
                            autocomplete="current-password"
                            autofocus
                            placeholder="••••••••"
                            class="folio-auth-input pl-11 pr-12"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-folio-outline-variant hover:text-folio-on-surface"
                            tabindex="-1"
                            @click="showPassword = !showPassword"
                        >
                            <span class="material-symbols-outlined"
                                >visibility</span
                            >
                        </button>
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container py-4 font-headline text-base font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all duration-200 hover:shadow-xl disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span>Continuar</span>
                    <span class="material-symbols-outlined text-lg"
                        >arrow_forward</span
                    >
                </button>
            </form>
        </div>
    </AuthSplitLayout>
</template>
