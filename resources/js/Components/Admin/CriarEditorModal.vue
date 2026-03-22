<script setup>
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';

const show = defineModel('show', { type: Boolean, default: false });

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function fechar() {
    if (form.processing) {
        return;
    }
    show.value = false;
}

function submeter() {
    form.post(route('admin.users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            show.value = false;
        },
    });
}
</script>

<template>
    <Modal
        :show="show"
        max-width="lg"
        :closeable="!form.processing"
        @close="fechar"
    >
        <div
            class="border-b border-folio-outline-variant/10 bg-folio-surface-low/80 px-6 py-4 sm:px-8"
            @click.stop
        >
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-folio-primary/10 text-folio-primary"
                        aria-hidden="true"
                    >
                        <span class="material-symbols-outlined text-2xl"
                            >person_add</span
                        >
                    </div>
                    <div>
                        <h3
                            class="font-headline text-lg font-bold text-folio-on-surface"
                        >
                            Novo editor
                        </h3>
                        <p class="mt-1 text-sm text-folio-secondary">
                            O usuário recebe o perfil <strong>editor</strong> e
                            pode entrar com o e-mail e a senha abaixo.
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    class="rounded-lg p-1.5 text-folio-outline transition-colors hover:bg-folio-surface-high hover:text-folio-on-surface"
                    :disabled="form.processing"
                    aria-label="Fechar"
                    @click="fechar"
                >
                    <span class="material-symbols-outlined text-[22px]"
                        >close</span
                    >
                </button>
            </div>
        </div>

        <form
            class="bg-white"
            @submit.prevent="submeter"
            @click.stop
        >
            <div class="space-y-4 px-6 py-5 sm:px-8">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5 sm:col-span-2">
                        <label
                            class="text-xs font-bold uppercase tracking-wider text-folio-outline"
                            for="criar-editor-nome"
                            >Nome</label
                        >
                        <input
                            id="criar-editor-nome"
                            v-model="form.name"
                            type="text"
                            required
                            autocomplete="name"
                            class="w-full rounded-lg border border-folio-outline-variant/30 px-3 py-2 text-sm text-folio-on-surface shadow-sm focus:border-folio-primary focus:outline-none focus:ring-2 focus:ring-folio-primary/20"
                        />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="space-y-1.5 sm:col-span-2">
                        <label
                            class="text-xs font-bold uppercase tracking-wider text-folio-outline"
                            for="criar-editor-email"
                            >E-mail</label
                        >
                        <input
                            id="criar-editor-email"
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="email"
                            class="w-full rounded-lg border border-folio-outline-variant/30 px-3 py-2 text-sm text-folio-on-surface shadow-sm focus:border-folio-primary focus:outline-none focus:ring-2 focus:ring-folio-primary/20"
                        />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-bold uppercase tracking-wider text-folio-outline"
                            for="criar-editor-senha"
                            >Senha</label
                        >
                        <input
                            id="criar-editor-senha"
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="w-full rounded-lg border border-folio-outline-variant/30 px-3 py-2 text-sm text-folio-on-surface shadow-sm focus:border-folio-primary focus:outline-none focus:ring-2 focus:ring-folio-primary/20"
                        />
                        <InputError :message="form.errors.password" />
                    </div>
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-bold uppercase tracking-wider text-folio-outline"
                            for="criar-editor-senha-conf"
                            >Confirmar senha</label
                        >
                        <input
                            id="criar-editor-senha-conf"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="w-full rounded-lg border border-folio-outline-variant/30 px-3 py-2 text-sm text-folio-on-surface shadow-sm focus:border-folio-primary focus:outline-none focus:ring-2 focus:ring-folio-primary/20"
                        />
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col-reverse gap-3 border-t border-folio-outline-variant/10 bg-folio-surface-low/40 px-6 py-4 sm:flex-row sm:justify-end sm:px-8"
            >
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-xl border border-folio-outline-variant/30 bg-white px-5 py-2.5 font-headline text-sm font-bold text-folio-on-surface transition-colors hover:bg-folio-surface-high disabled:opacity-50"
                    :disabled="form.processing"
                    @click="fechar"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-folio-primary px-5 py-2.5 font-headline text-sm font-bold text-folio-on-primary shadow-sm transition-colors hover:opacity-95 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span
                        v-if="form.processing"
                        class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-folio-on-primary border-t-transparent"
                        aria-hidden="true"
                    />
                    <span
                        v-else
                        class="material-symbols-outlined text-lg"
                        aria-hidden="true"
                        >save</span
                    >
                    {{ form.processing ? 'Salvando…' : 'Salvar editor' }}
                </button>
            </div>
        </form>
    </Modal>
</template>
