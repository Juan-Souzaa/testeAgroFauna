<script setup>
import AuthSplitLayout from '@/Layouts/AuthSplitLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Recuperar senha" />

    <AuthSplitLayout
        hero-title="Recuperar o acesso"
        hero-paragraph="Indique o e-mail da sua conta e enviaremos um link para definir uma nova senha com segurança."
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
                    Esqueceu a senha?
                </h2>
            </div>

            <div class="space-y-6 p-8">
                <p class="text-sm leading-relaxed text-folio-secondary">
                    Sem problema. Digite o e-mail associado à conta e receberá um
                    link para escolher uma nova senha.
                </p>

                <div
                    v-if="status"
                    class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
                >
                    {{ status }}
                </div>

                <form class="space-y-6" @submit.prevent="submit">
                    <div class="space-y-2">
                        <label
                            class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                            for="email"
                            >Endereço de e-mail</label
                        >
                        <div class="group relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                            >
                                <span
                                    class="material-symbols-outlined text-folio-outline-variant transition-colors group-focus-within:text-folio-primary"
                                    >mail</span
                                >
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="seu@email.com"
                                class="folio-auth-input pl-11 pr-4"
                            />
                        </div>
                        <InputError :message="form.errors.email" />
                    </div>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container py-4 font-headline text-base font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all duration-200 hover:shadow-xl hover:shadow-folio-primary/30 active:scale-[0.98] disabled:opacity-60"
                        :disabled="form.processing"
                    >
                        <span>Enviar link de recuperação</span>
                        <span class="material-symbols-outlined text-lg"
                            >mail</span
                        >
                    </button>

                    <div class="text-center">
                        <Link
                            :href="route('login')"
                            class="text-sm font-semibold text-folio-primary hover:text-folio-primary-container"
                        >
                            ← Voltar ao login
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthSplitLayout>
</template>
