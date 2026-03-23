<script setup>
import { usePage } from '@inertiajs/vue3';
import { onBeforeUnmount, ref, watch } from 'vue';

const page = usePage();

const visivel = ref(false);
const mensagem = ref('');


const AUTO_DISMISS_MS = 6000;

let timeoutId = null;

function limparTimeout() {
    if (timeoutId !== null) {
        clearTimeout(timeoutId);
        timeoutId = null;
    }
}

function iniciarAutoFechar() {
    limparTimeout();
    timeoutId = window.setTimeout(() => {
        visivel.value = false;
        timeoutId = null;
    }, AUTO_DISMISS_MS);
}

function fechar() {
    limparTimeout();
    visivel.value = false;
}

watch(
    () => page.props.flash?.success,
    (msg) => {
        limparTimeout();
        if (msg) {
            mensagem.value = msg;
            visivel.value = true;
            iniciarAutoFechar();
        } else {
            visivel.value = false;
            mensagem.value = '';
        }
    },
    { immediate: true },
);

onBeforeUnmount(limparTimeout);
</script>

<template>
    <div
        v-if="visivel && mensagem"
        role="status"
        aria-live="polite"
        class="mx-auto mb-6 max-w-7xl px-4 pt-6 sm:px-6 lg:px-10"
    >
        <div
            class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm"
        >
            <span
                class="material-symbols-outlined mt-0.5 shrink-0 text-[20px] text-emerald-600"
                aria-hidden="true"
                >check_circle</span
            >
            <p class="min-w-0 flex-1 leading-relaxed">
                {{ mensagem }}
            </p>
            <button
                type="button"
                class="-m-1 shrink-0 rounded-lg p-1.5 text-emerald-700 transition-colors hover:bg-emerald-100 hover:text-emerald-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500"
                aria-label="Fechar notificação"
                @click="fechar"
            >
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    </div>
</template>
