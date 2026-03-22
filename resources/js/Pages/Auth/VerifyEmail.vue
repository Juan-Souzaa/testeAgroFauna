<script setup>
import { computed } from 'vue';
import AuthSplitLayout from '@/Layouts/AuthSplitLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <Head title="Verificar e-mail" />

    <AuthSplitLayout
        hero-title="Quase lá"
        hero-paragraph="Só falta confirmar o endereço de e-mail para ativar o acesso completo ao painel."
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
                    Verificar e-mail
                </h2>
            </div>

            <div class="space-y-6 p-8">
                <p class="text-sm leading-relaxed text-folio-secondary">
                    Obrigado pelo cadastro. Antes de começar, confirme seu
                    e-mail pelo link que enviamos para sua caixa de entrada.
                    Se você não recebeu a mensagem, pode solicitar um novo envio.
                </p>

                <div
                    v-if="verificationLinkSent"
                    class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
                >
                    Enviamos um novo link de verificação para o e-mail usado no
                    cadastro.
                </div>

                <form
                    class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                    @submit.prevent="submit"
                >
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container px-6 py-3 font-headline text-sm font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all hover:shadow-xl disabled:opacity-60"
                        :disabled="form.processing"
                    >
                        Reenviar e-mail de verificação
                    </button>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="rounded-xl border border-folio-outline-variant/30 bg-folio-surface-low px-5 py-3 text-center text-sm font-semibold text-folio-secondary transition-colors hover:bg-folio-surface-high"
                    >
                        Sair
                    </Link>
                </form>
            </div>
        </div>
    </AuthSplitLayout>
</template>
