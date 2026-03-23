<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CriarEditorModal from '@/Components/Admin/CriarEditorModal.vue';
import ExcluirUsuarioModal from '@/Components/Admin/ExcluirUsuarioModal.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();

const modalCriarAberto = ref(false);
const modalExcluirAberto = ref(false);
const usuarioParaExcluir = ref(null);
const excluindoUsuario = ref(false);

const idUsuarioAutenticado = computed(() => page.props.auth?.user?.id ?? null);

watch(modalExcluirAberto, (aberto) => {
    if (!aberto) {
        usuarioParaExcluir.value = null;
    }
});

const props = defineProps({
    usuarios: {
        type: Object,
        required: true,
    },
    papeis: {
        type: Array,
        required: true,
    },
    estatisticas: {
        type: Object,
        required: true,
    },
});

const labelPapel = {
    admin: 'Administrador',
    editor: 'Editor',
};

const papelAtual = (user) => user.roles?.[0]?.name ?? '';

function aoMudarPapel(user, event) {
    const role = event.target.value;
    router.patch(
        route('admin.users.role', user.id),
        { role },
        { preserveScroll: true },
    );
}

function podeExcluirUsuario(u) {
    if (idUsuarioAutenticado.value === null || u.id === idUsuarioAutenticado.value) {
        return false;
    }
    if (
        props.estatisticas.admins <= 1 &&
        papelAtual(u) === 'admin'
    ) {
        return false;
    }
    return true;
}

function abrirExcluirUsuario(u) {
    if (!podeExcluirUsuario(u)) {
        return;
    }
    usuarioParaExcluir.value = u;
    modalExcluirAberto.value = true;
}

function confirmarExclusaoUsuario() {
    const u = usuarioParaExcluir.value;
    if (!u) {
        return;
    }
    excluindoUsuario.value = true;
    router.delete(route('admin.users.destroy', u.id), {
        preserveScroll: true,
        onFinish: () => {
            excluindoUsuario.value = false;
        },
        onSuccess: () => {
            modalExcluirAberto.value = false;
        },
    });
}

function iniciais(nome) {
    const partes = (nome || '?').trim().split(/\s+/).filter(Boolean);
    if (partes.length === 0) {
        return '?';
    }
    if (partes.length === 1) {
        return partes[0].slice(0, 2).toUpperCase();
    }
    return (partes[0][0] + partes[partes.length - 1][0]).toUpperCase();
}

const intervaloLista = computed(() => {
    const u = props.usuarios;
    if (!u.total) {
        return null;
    }
    return {
        de: u.from ?? 0,
        ate: u.to ?? 0,
        total: u.total,
    };
});
</script>

<template>
    <Head title="Controle de acesso" />

    <AuthenticatedLayout>
        <div
            class="mx-auto max-w-7xl px-4 pb-12 pt-6 sm:px-6 lg:px-12 lg:pt-8"
        >
            <div v-if="$page.props.errors?.role" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                <p class="font-medium text-red-800">
                    {{ $page.props.errors.role }}
                </p>
            </div>

            <div v-if="$page.props.errors?.user" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                <p class="font-medium text-red-800">
                    {{ $page.props.errors.user }}
                </p>
            </div>

            <!-- Hero (Stitch: Controle de Acesso) -->
            <div
                class="mb-10 flex flex-col gap-6 sm:mb-12 sm:flex-row sm:items-end sm:justify-between"
            >
                <div class="space-y-2">
                    <h1
                        class="font-headline text-3xl font-extrabold tracking-tight text-folio-primary sm:text-4xl"
                    >
                        Controle de acesso
                    </h1>
                    <p
                        class="max-w-lg text-sm font-medium text-folio-secondary sm:text-base"
                    >
                        Gerencie permissões e papéis da equipe. Novos acessos são
                        criados aqui (perfil editor). O registro público está
                        desativado. Editores gerenciam livros; administradores
                        excluem livros e esta área.
                    </p>
                </div>
                <button
                    type="button"
                    class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-folio-primary px-6 py-3 text-sm font-semibold text-folio-on-primary shadow-md transition-opacity hover:opacity-95"
                    :aria-expanded="modalCriarAberto"
                    aria-haspopup="dialog"
                    @click="modalCriarAberto = true"
                >
                    <span class="material-symbols-outlined text-[20px]"
                        >person_add</span
                    >
                    Criar editor
                </button>
            </div>

            <CriarEditorModal v-model:show="modalCriarAberto" />

            <ExcluirUsuarioModal
                v-model:show="modalExcluirAberto"
                :nome-usuario="usuarioParaExcluir?.name ?? ''"
                :processing="excluindoUsuario"
                @confirm="confirmarExclusaoUsuario"
            />

            <!-- Cards estilo Stitch / bento -->
            <div class="mb-10 grid grid-cols-1 gap-6 sm:mb-12 md:grid-cols-3">
                <div
                    class="flex items-center gap-4 rounded-xl border border-transparent bg-white p-6 shadow-sm transition-colors hover:bg-folio-surface-bright"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-folio-primary-fixed text-folio-primary"
                    >
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <div>
                        <p
                            class="text-xs font-bold uppercase tracking-wider text-folio-outline"
                        >
                            Total da equipe
                        </p>
                        <p
                            class="font-headline text-2xl font-extrabold text-folio-primary"
                        >
                            {{ estatisticas.total }}
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center gap-4 rounded-xl border border-transparent bg-white p-6 shadow-sm transition-colors hover:bg-folio-surface-bright"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-folio-secondary-container text-folio-on-secondary-container"
                    >
                        <span class="material-symbols-outlined"
                            >verified_user</span
                        >
                    </div>
                    <div>
                        <p
                            class="text-xs font-bold uppercase tracking-wider text-folio-outline"
                        >
                            Administradores
                        </p>
                        <p
                            class="font-headline text-2xl font-extrabold text-folio-primary"
                        >
                            {{ estatisticas.admins }}
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center gap-4 rounded-xl border border-transparent bg-white p-6 shadow-sm transition-colors hover:bg-folio-surface-bright"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-folio-primary-fixed text-folio-on-tertiary-fixed"
                    >
                        <span class="material-symbols-outlined"
                            >edit_document</span
                        >
                    </div>
                    <div>
                        <p
                            class="text-xs font-bold uppercase tracking-wider text-folio-outline"
                        >
                            Editores
                        </p>
                        <p
                            class="font-headline text-2xl font-extrabold text-folio-primary"
                        >
                            {{ estatisticas.editores }}
                        </p>
                    </div>
                </div>
            </div>

            

            <!-- Tabela estilo ledger -->
            <div
                class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200/80"
            >
                <div
                    class="flex flex-col gap-4 border-b border-folio-outline-variant/10 bg-folio-surface-low/80 p-6 sm:flex-row sm:items-center sm:justify-between"
                >
                    <h2
                        class="font-headline text-lg font-bold text-folio-primary"
                    >
                        Registro da equipe
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <span
                            class="inline-flex cursor-not-allowed items-center gap-1 rounded-lg border border-slate-100 bg-white px-3 py-1.5 text-xs font-bold text-slate-400 opacity-60 shadow-sm"
                            title="Em breve"
                        >
                            <span class="material-symbols-outlined text-sm"
                                >filter_list</span
                            >
                            Filtrar
                        </span>
                        <span
                            class="inline-flex cursor-not-allowed items-center gap-1 rounded-lg border border-slate-100 bg-white px-3 py-1.5 text-xs font-bold text-slate-400 opacity-60 shadow-sm"
                            title="Em breve"
                        >
                            <span class="material-symbols-outlined text-sm"
                                >download</span
                            >
                            Exportar
                        </span>
                    </div>
                </div>

                <p
                    v-if="usuarios.data.length === 0"
                    class="px-6 py-14 text-center text-sm text-folio-secondary"
                >
                    Nenhum usuário encontrado.
                </p>

                <div v-else class="overflow-x-auto">
                    <table class="w-full min-w-[62rem] border-collapse text-left">
                        <thead>
                            <tr class="bg-folio-surface-low/50">
                                <th
                                    class="px-6 py-4 font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline"
                                >
                                    Nome
                                </th>
                                <th
                                    class="px-6 py-4 font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline"
                                >
                                    E-mail
                                </th>
                                <th
                                    class="px-6 py-4 font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline"
                                >
                                    Perfil
                                </th>
                                <th
                                    class="px-6 py-4 font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline"
                                >
                                    Estado
                                </th>
                                <th
                                    class="px-6 py-4 text-right font-headline text-[11px] font-bold uppercase tracking-widest text-folio-outline"
                                >
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="u in usuarios.data"
                                :key="u.id"
                                class="group transition-colors hover:bg-folio-surface-low/80"
                            >
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-folio-primary/10 font-headline text-xs font-bold text-folio-primary"
                                        >
                                            {{ iniciais(u.name) }}
                                        </div>
                                        <span
                                            class="font-semibold text-folio-primary"
                                            >{{ u.name }}</span
                                        >
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-5 text-sm text-folio-secondary"
                                >
                                    {{ u.email }}
                                </td>
                                <td class="px-6 py-5">
                                    <select
                                        class="max-w-[14rem] cursor-pointer rounded-lg border border-folio-outline-variant/30 bg-white py-2 pl-3 pr-8 text-sm font-semibold text-folio-on-surface shadow-sm focus:ring-2 focus:ring-folio-primary/20"
                                        :value="papelAtual(u)"
                                        @change="aoMudarPapel(u, $event)"
                                    >
                                        <option
                                            v-for="p in papeis"
                                            :key="p"
                                            :value="p"
                                        >
                                            {{ labelPapel[p] ?? p }}
                                        </option>
                                    </select>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="h-2 w-2 shrink-0 rounded-full bg-emerald-500"
                                            aria-hidden="true"
                                        />
                                        <span
                                            class="text-xs font-medium text-folio-on-surface-variant"
                                            >Ativo</span
                                        >
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center rounded-lg p-2 text-folio-outline transition-colors hover:bg-red-50 hover:text-red-700 disabled:cursor-not-allowed disabled:opacity-40"
                                        :disabled="!podeExcluirUsuario(u)"
                                        :title="
                                            !podeExcluirUsuario(u)
                                                ? u.id === idUsuarioAutenticado
                                                    ? 'Você não pode excluir a própria conta aqui.'
                                                    : 'Não é possível excluir o único administrador.'
                                                : 'Excluir usuário'
                                        "
                                        @click="abrirExcluirUsuario(u)"
                                    >
                                        <span
                                            class="material-symbols-outlined text-[22px]"
                                            aria-hidden="true"
                                            >delete</span
                                        >
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="
                        usuarios.total > 0 &&
                        usuarios.links &&
                        usuarios.links.length > 3
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
                        usuários
                    </p>
                    <nav
                        class="flex flex-wrap items-center justify-center gap-1"
                        aria-label="Paginação"
                    >
                        <template
                            v-for="link in usuarios.links"
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
                Livraria · controle de acesso
            </p>
        </div>
    </AuthenticatedLayout>
</template>
