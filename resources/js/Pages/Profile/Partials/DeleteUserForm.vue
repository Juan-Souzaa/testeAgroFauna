<script setup>
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <div
            class="border-b border-folio-outline-variant/10 bg-folio-surface-low/80 px-6 py-4 sm:px-8"
        >
            <h2 class="font-headline text-base font-bold text-folio-on-surface">
                Excluir conta
            </h2>
            <p class="mt-1 text-sm text-folio-secondary">
                Ação irreversível. Baixe ou anote o que precisar antes de
                continuar.
            </p>
        </div>

        <div class="p-6 sm:p-8">
            <button
                type="button"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-6 py-3.5 font-headline text-sm font-bold text-red-800 transition-colors hover:bg-red-100"
                @click="confirmUserDeletion"
            >
                <span class="material-symbols-outlined text-lg"
                    >delete_forever</span
                >
                Excluir minha conta
            </button>
        </div>

        <Modal
            :show="confirmingUserDeletion"
            max-width="md"
            :closeable="!form.processing"
            @close="closeModal"
        >
            <div class="flex max-h-[90vh] flex-col overflow-hidden">
                <div
                    class="flex shrink-0 items-start gap-3 border-b border-folio-outline-variant/10 bg-folio-surface-low/80 px-6 py-4 sm:px-8"
                >
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700"
                        aria-hidden="true"
                    >
                        <span class="material-symbols-outlined text-2xl"
                            >warning</span
                        >
                    </div>
                    <div class="min-w-0">
                        <h3
                            class="font-headline text-lg font-bold text-folio-on-surface"
                        >
                            Excluir conta permanentemente?
                        </h3>
                        <p class="mt-1 text-sm text-folio-secondary">
                            Todos os dados serão apagados. Digite sua senha para
                            confirmar.
                        </p>
                    </div>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto px-6 py-5 sm:px-8">
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                        for="delete-password"
                        >Senha</label
                    >
                    <div class="group relative mt-2">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                        >
                            <span
                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                >lock</span
                            >
                        </div>
                        <input
                            id="delete-password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="folio-auth-input pl-11 pr-4"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            @keyup.enter="deleteUser"
                        />
                    </div>
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div
                    class="flex shrink-0 flex-col-reverse gap-3 border-t border-folio-outline-variant/10 bg-white px-6 py-4 sm:flex-row sm:justify-end sm:px-8"
                >
                    <button
                        type="button"
                        class="inline-flex w-full items-center justify-center rounded-xl border border-folio-outline-variant/30 bg-folio-surface-low px-5 py-2.5 font-headline text-sm font-bold text-folio-on-surface transition-colors hover:bg-folio-surface-high sm:w-auto"
                        @click="closeModal"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 font-headline text-sm font-bold text-white shadow-sm transition-colors hover:bg-red-700 disabled:opacity-50 sm:w-auto"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        <span
                            v-if="form.processing"
                            class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                            aria-hidden="true"
                        />
                        <span
                            v-else
                            class="material-symbols-outlined text-lg"
                            >delete_forever</span
                        >
                        {{ form.processing ? 'Excluindo…' : 'Excluir conta' }}
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
