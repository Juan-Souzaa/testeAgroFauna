<script setup>
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    nomeUsuario: {
        type: String,
        default: '',
    },
    processing: {
        type: Boolean,
        default: false,
    },
});

const show = defineModel('show', { type: Boolean, default: false });

const emit = defineEmits(['confirm']);

function handleConfirm() {
    if (props.processing) {
        return;
    }
    emit('confirm');
}

function fechar() {
    if (props.processing) {
        return;
    }
    show.value = false;
}
</script>

<template>
    <Modal
        :show="show"
        max-width="md"
        :closeable="!processing"
        @close="fechar"
    >
        <div
            class="border-b border-folio-outline-variant/10 bg-folio-surface-low/80 px-6 py-4 sm:px-8"
            @click.stop
        >
            <div class="flex items-start gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700"
                    aria-hidden="true"
                >
                    <span class="material-symbols-outlined text-2xl"
                        >person_off</span
                    >
                </div>
                <div class="min-w-0 flex-1">
                    <h3
                        class="font-headline text-lg font-bold text-folio-on-surface"
                    >
                        Excluir usuário?
                    </h3>
                    <p class="mt-1 text-sm text-folio-secondary">
                        Tem certeza de que deseja remover
                        <span class="font-semibold text-folio-on-surface">{{
                            nomeUsuario
                        }}</span
                        >? Ele perderá o acesso ao painel imediatamente.
                    </p>
                </div>
            </div>
        </div>

        <div
            class="flex flex-col-reverse gap-3 bg-white px-6 py-4 sm:flex-row sm:justify-end sm:px-8"
            @click.stop
        >
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-xl border border-folio-outline-variant/30 bg-folio-surface-low px-5 py-2.5 font-headline text-sm font-bold text-folio-on-surface transition-colors hover:bg-folio-surface-high disabled:opacity-50"
                :disabled="processing"
                @click="fechar"
            >
                Cancelar
            </button>
            <button
                type="button"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 font-headline text-sm font-bold text-white shadow-sm transition-colors hover:bg-red-700 disabled:opacity-60"
                :disabled="processing"
                @click="handleConfirm"
            >
                <span
                    v-if="processing"
                    class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                    aria-hidden="true"
                />
                <span
                    v-else
                    class="material-symbols-outlined text-lg"
                    aria-hidden="true"
                    >delete</span
                >
                {{ processing ? 'Excluindo…' : 'Excluir usuário' }}
            </button>
        </div>
    </Modal>
</template>
