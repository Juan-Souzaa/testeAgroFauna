<script setup>
import AuthSplitLayout from '@/Layouts/AuthSplitLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Nova senha" />

    <AuthSplitLayout
        hero-title="Defina uma nova senha"
        hero-paragraph="Escolha uma senha forte e guarde-a num gestor de credenciais sempre que possível."
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
                    Redefinir senha
                </h2>
            </div>

            <form class="space-y-5 p-8" @submit.prevent="submit">
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
                                class="material-symbols-outlined text-folio-outline-variant"
                                >mail</span
                            >
                        </div>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            readonly
                            autocomplete="username"
                            class="folio-auth-input-readonly pl-11 pr-4"
                        />
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-2">
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                        for="password"
                        >Nova senha</label
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
                            autofocus
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
                        >Confirmar nova senha</label
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

                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container py-4 font-headline text-base font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all duration-200 hover:shadow-xl disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span>Salvar nova senha</span>
                    <span class="material-symbols-outlined text-lg"
                        >check</span
                    >
                </button>
            </form>
        </div>
    </AuthSplitLayout>
</template>
