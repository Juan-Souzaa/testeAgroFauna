<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <div
            class="border-b border-folio-outline-variant/10 bg-folio-surface-low/80 px-6 py-4 sm:px-8"
        >
            <h2 class="font-headline text-base font-bold text-folio-on-surface">
                Dados pessoais
            </h2>
            <p class="mt-1 text-sm text-folio-secondary">
                Nome e e-mail usados para login e avisos do sistema.
            </p>
        </div>

        <form
            class="p-6 sm:p-8"
            @submit.prevent="form.patch(route('profile.update'))"
        >
            <div class="space-y-6">
                <div class="space-y-2">
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                        for="name"
                        >Nome</label
                    >
                    <div class="group relative">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                        >
                            <span
                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
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
                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                >mail</span
                            >
                        </div>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="username"
                            class="folio-auth-input pl-11 pr-4"
                        />
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <div
                    v-if="mustVerifyEmail && user.email_verified_at === null"
                    class="rounded-xl border border-amber-200/80 bg-amber-50/90 px-4 py-3 text-sm text-amber-950"
                >
                    <p class="font-medium">
                        O seu e-mail ainda não foi confirmado.
                    </p>
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="mt-2 inline-flex text-sm font-bold text-folio-primary underline decoration-2 underline-offset-2 hover:text-folio-primary-container"
                    >
                        Reenviar e-mail de confirmação
                    </Link>
                    <p
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 text-sm font-semibold text-emerald-800"
                    >
                        Enviamos um novo link para o seu e-mail.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container px-6 py-3 font-headline text-sm font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all hover:shadow-xl disabled:opacity-60"
                        :disabled="form.processing"
                    >
                        <span class="material-symbols-outlined text-lg">save</span>
                        Salvar
                    </button>
                    <Transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in duration-150"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-if="form.recentlySuccessful"
                            class="text-sm font-medium text-emerald-700"
                        >
                            Alterações salvas.
                        </p>
                    </Transition>
                </div>
            </div>
        </form>
    </section>
</template>
