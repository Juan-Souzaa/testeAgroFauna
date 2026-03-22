<script setup>
import AuthSplitLayout from '@/Layouts/AuthSplitLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Entrar" />

    <AuthSplitLayout>
        <div
            class="overflow-hidden rounded-2xl border border-folio-outline-variant/10 bg-white shadow-folio"
        >
            <div
                class="border-b border-folio-outline-variant/10 bg-folio-surface-low px-8 py-5"
            >
                <h2
                    class="font-headline text-lg font-bold text-folio-on-surface"
                >
                    Login seguro
                </h2>
            </div>

            <form class="space-y-6 p-8" @submit.prevent="submit">
                <div
                    v-if="status"
                    class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
                >
                    {{ status }}
                </div>

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
                            :class="[inputClass, 'pl-11 pr-4']"
                        />
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label
                            class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                            for="password"
                            >Senha</label
                        >
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-xs font-semibold text-folio-primary transition-colors hover:text-folio-primary-container"
                        >
                            Esqueceu a senha?
                        </Link>
                    </div>
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
                            placeholder="••••••••"
                            class="folio-auth-input pl-11 pr-12"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-folio-outline-variant transition-colors hover:text-folio-on-surface"
                            tabindex="-1"
                            :aria-pressed="showPassword"
                            :title="
                                showPassword
                                    ? 'Ocultar senha'
                                    : 'Mostrar senha'
                            "
                            @click="showPassword = !showPassword"
                        >
                            <span class="material-symbols-outlined"
                                >visibility</span
                            >
                        </button>
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center gap-3 py-1">
                    <input
                        id="remember"
                        v-model="form.remember"
                        type="checkbox"
                        class="h-5 w-5 cursor-pointer rounded-lg border-folio-outline-variant bg-folio-surface-highest text-folio-primary focus:ring-folio-primary/20"
                    />
                    <label
                        class="cursor-pointer select-none text-sm font-medium text-folio-secondary"
                        for="remember"
                        >Mantenha-me conectado neste dispositivo</label
                    >
                </div>

                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container py-4 font-headline text-base font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all duration-200 hover:shadow-xl hover:shadow-folio-primary/30 active:scale-[0.98] disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span>Entrar</span>
                    <span class="material-symbols-outlined text-lg"
                        >arrow_forward</span
                    >
                </button>
            </form>
        </div>
    </AuthSplitLayout>
</template>
