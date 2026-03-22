<script setup>
import AuthSplitLayout from '@/Layouts/AuthSplitLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Criar conta" />

    <AuthSplitLayout
        hero-title="Crie a sua conta"
        hero-paragraph="Aceda ao painel administrativo com um único conjunto de credenciais seguras."
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
                    Novo usuário
                </h2>
            </div>

            <form class="space-y-5 p-8" @submit.prevent="submit">
                <div class="space-y-2">
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                        for="name"
                        >Nome completo</label
                    >
                    <div class="group relative">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                        >
                            <span
                                class="material-symbols-outlined text-folio-outline-variant transition-colors group-focus-within:text-folio-primary"
                                >person</span
                            >
                        </div>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="O seu nome"
                            class="folio-auth-input pl-11 pr-4"
                        />
                    </div>
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                        for="email"
                        >E-mail</label
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
                            autocomplete="username"
                            placeholder="seu@email.com"
                            class="folio-auth-input pl-11 pr-4"
                        />
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

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
                            autocomplete="new-password"
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

                <div class="space-y-2">
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                        for="password_confirmation"
                        >Confirmar senha</label
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
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            :type="
                                showPasswordConfirmation ? 'text' : 'password'
                            "
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="folio-auth-input pl-11 pr-12"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-folio-outline-variant hover:text-folio-on-surface"
                            tabindex="-1"
                            @click="
                                showPasswordConfirmation =
                                    !showPasswordConfirmation
                            "
                        >
                            <span class="material-symbols-outlined"
                                >visibility</span
                            >
                        </button>
                    </div>
                    <InputError
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div
                    class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between"
                >
                    <Link
                        :href="route('login')"
                        class="text-center text-sm font-semibold text-folio-secondary hover:text-folio-primary sm:text-left"
                    >
                        Já tem conta? Entrar
                    </Link>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container px-6 py-3 font-headline text-sm font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all hover:shadow-xl disabled:opacity-60 sm:min-w-[160px]"
                        :disabled="form.processing"
                    >
                        Criar conta
                    </button>
                </div>
            </form>
        </div>
    </AuthSplitLayout>
</template>
