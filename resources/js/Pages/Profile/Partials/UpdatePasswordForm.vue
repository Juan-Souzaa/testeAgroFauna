<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <div
            class="border-b border-folio-outline-variant/10 bg-folio-surface-low/80 px-6 py-4 sm:px-8"
        >
            <h2 class="font-headline text-base font-bold text-folio-on-surface">
                Senha
            </h2>
            <p class="mt-1 text-sm text-folio-secondary">
                Use uma senha longa e única. Um gerenciador de senhas ajuda a
                manter tudo seguro.
            </p>
        </div>

        <form class="p-6 sm:p-8" @submit.prevent="updatePassword">
            <div class="space-y-6">
                <div class="space-y-2">
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                        for="current_password"
                        >Senha atual</label
                    >
                    <div class="group relative">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                        >
                            <span
                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                >lock</span
                            >
                        </div>
                        <input
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            autocomplete="current-password"
                            class="folio-auth-input pl-11 pr-4"
                        />
                    </div>
                    <InputError :message="form.errors.current_password" />
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
                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                >key</span
                            >
                        </div>
                        <input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            class="folio-auth-input pl-11 pr-4"
                        />
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
                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                >verified_user</span
                            >
                        </div>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="folio-auth-input pl-11 pr-4"
                        />
                    </div>
                    <InputError
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container px-6 py-3 font-headline text-sm font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all hover:shadow-xl disabled:opacity-60"
                        :disabled="form.processing"
                    >
                        <span class="material-symbols-outlined text-lg"
                            >password</span
                        >
                        Atualizar senha
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
                            Senha atualizada.
                        </p>
                    </Transition>
                </div>
            </div>
        </form>
    </section>
</template>
