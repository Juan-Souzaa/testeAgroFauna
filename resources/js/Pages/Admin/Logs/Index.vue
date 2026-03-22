<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    logs: {
        type: Object,
        required: true,
    },
});

const rotulosAcao = {
    'livro.created': 'Livro criado',
    'livro.updated': 'Livro atualizado',
    'livro.deleted': 'Livro excluído',
    'usuario.created': 'Usuário criado',
    'usuario.role_changed': 'Papel alterado',
    'usuario.deleted': 'Usuário excluído',
};

function rotuloAcao(acao) {
    return rotulosAcao[acao] ?? acao;
}

function formatarDataHora(iso) {
    if (iso == null || iso === '') {
        return '—';
    }
    const d = new Date(iso);
    if (Number.isNaN(d.getTime())) {
        return '—';
    }
    return d.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

const intervaloLista = computed(() => {
    const l = props.logs;
    if (!l.total) {
        return null;
    }
    return {
        de: l.from ?? 0,
        ate: l.to ?? 0,
        total: l.total,
    };
});
</script>

<template>
    <Head title="Registro de atividades" />

    <AuthenticatedLayout>
        <div
            class="mx-auto max-w-7xl px-4 pb-12 pt-6 sm:px-6 lg:px-12 lg:pt-8"
        >
            <div class="mb-10 space-y-2">
                <h1
                    class="font-headline text-3xl font-extrabold tracking-tight text-folio-primary sm:text-4xl"
                >
                    Registro de atividades
                </h1>
                <p
                    class="max-w-2xl text-sm font-medium text-folio-secondary sm:text-base"
                >
                    Histórico de ações relevantes no catálogo e na gestão de
                    usuários (quem fez o quê e quando).
                </p>
            </div>

            <div
                class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200/80"
            >
                <p
                    v-if="logs.data.length === 0"
                    class="px-6 py-14 text-center text-sm text-folio-secondary"
                >
                    Nenhum registro ainda.
                </p>

                <div v-else class="overflow-x-auto">
                    <table class="w-full min-w-[56rem] border-collapse text-left">
                        <thead>
                            <tr class="bg-folio-surface-low/50">
                                <th
                                    class="px-4 py-4 font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline sm:px-6"
                                >
                                    Data
                                </th>
                                <th
                                    class="px-4 py-4 font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline sm:px-6"
                                >
                                    Quem
                                </th>
                                <th
                                    class="px-4 py-4 font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline sm:px-6"
                                >
                                    Ação
                                </th>
                                <th
                                    class="px-4 py-4 font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline sm:px-6"
                                >
                                    Detalhe
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="log in logs.data"
                                :key="log.id"
                                class="border-t border-folio-outline-variant/10 transition-colors hover:bg-folio-surface-low/80"
                            >
                                <td
                                    class="whitespace-nowrap px-4 py-4 text-sm text-folio-secondary sm:px-6"
                                >
                                    {{ formatarDataHora(log.created_at) }}
                                </td>
                                <td class="px-4 py-4 text-sm sm:px-6">
                                    <span
                                        v-if="log.user"
                                        class="font-medium text-folio-on-surface"
                                        >{{ log.user.name }}</span
                                    >
                                    <span
                                        v-else
                                        class="text-folio-outline-variant"
                                        >—</span
                                    >
                                </td>
                                <td class="px-4 py-4 sm:px-6">
                                    <span
                                        class="inline-flex rounded-full bg-folio-primary-fixed px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-folio-primary"
                                    >
                                        {{ rotuloAcao(log.action) }}
                                    </span>
                                </td>
                                <td
                                    class="max-w-md px-4 py-4 text-sm text-folio-secondary sm:px-6"
                                >
                                    {{ log.description ?? '—' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="
                        logs.total > 0 &&
                        logs.links &&
                        logs.links.length > 3
                    "
                    class="flex flex-col gap-4 border-t border-folio-outline-variant/15 bg-folio-surface-high/30 px-6 py-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <p
                        v-if="intervaloLista"
                        class="text-xs font-medium text-folio-outline"
                    >
                        Mostrando
                        <span class="font-bold text-folio-on-surface">{{
                            intervaloLista.de
                        }}</span>
                        –
                        <span class="font-bold text-folio-on-surface">{{
                            intervaloLista.ate
                        }}</span>
                        de
                        <span class="font-bold text-folio-on-surface">{{
                            intervaloLista.total
                        }}</span>
                        registros
                    </p>
                    <nav
                        class="flex flex-wrap items-center justify-center gap-1"
                        aria-label="Paginação"
                    >
                        <template
                            v-for="link in logs.links"
                            :key="`${link.label}-${link.url ?? 'c'}`"
                        >
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                preserve-scroll
                                class="inline-flex min-h-8 min-w-8 items-center justify-center rounded-lg px-2 text-sm font-bold transition-colors"
                                :class="
                                    link.active
                                        ? 'rounded-full bg-folio-primary text-folio-on-primary'
                                        : 'text-folio-secondary hover:bg-white'
                                "
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="inline-flex min-h-8 min-w-8 items-center justify-center px-2 text-sm text-folio-outline-variant"
                                v-html="link.label"
                            />
                        </template>
                    </nav>
                </div>
            </div>

            <p
                class="mt-10 text-center text-[10px] font-bold uppercase tracking-widest text-folio-outline/80"
            >
                Livraria · auditoria
            </p>
        </div>
    </AuthenticatedLayout>
</template>
