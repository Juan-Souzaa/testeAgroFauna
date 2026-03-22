<script setup>
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatarData, formatarPreco } from '@/utils/format';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();

const props = defineProps({
    livros: {
        type: Object,
        required: true,
    },
    porPaginaOpcoes: {
        type: Array,
        required: true,
    },
});

function mudarPorPagina(event) {
    const perPage = Number(event.target.value);
    router.get(
        route('livros.index'),
        { per_page: perPage, page: 1 },
        { preserveScroll: true },
    );
}

const podeCadastrar = computed(() =>
    (page.props.permissions ?? []).includes('books.create'),
);

const podeEditar = computed(() =>
    (page.props.permissions ?? []).includes('books.update'),
);

const podeExcluir = computed(() =>
    (page.props.permissions ?? []).includes('books.delete'),
);

function iniciais(titulo) {
    const t = (titulo || '?').trim();
    return t.slice(0, 1).toUpperCase();
}

const modalExcluirAberto = ref(false);
const livroParaExcluir = ref(null);
const aExcluir = ref(false);


const livroExpandidoId = ref(null);

function alternarExpansaoLivro(id) {
    livroExpandidoId.value = livroExpandidoId.value === id ? null : id;
}

watch(
    () => props.livros.data.map((l) => l.id).join(','),
    () => {
        livroExpandidoId.value = null;
    },
);

function abrirModalExcluir(livro) {
    livroParaExcluir.value = livro;
    modalExcluirAberto.value = true;
    livroExpandidoId.value = null;
}

function fecharModalExcluir() {
    if (aExcluir.value) {
        return;
    }
    modalExcluirAberto.value = false;
    livroParaExcluir.value = null;
}

function executarExclusao() {
    if (!livroParaExcluir.value) {
        return;
    }
    aExcluir.value = true;
    router.delete(route('livros.destroy', livroParaExcluir.value.id), {
        preserveScroll: true,
        onFinish: () => {
            aExcluir.value = false;
            modalExcluirAberto.value = false;
            livroParaExcluir.value = null;
        },
    });
}

function chipCategoria(livro) {
    const id = livro.categoria_id ?? 0;
    return id % 2 === 0
        ? 'bg-folio-secondary-container text-folio-on-secondary-container'
        : 'bg-folio-primary-container text-folio-on-primary';
}

const intervaloLista = computed(() => {
    const l = props.livros;
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
    <Head title="Livro de Estoque" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 pb-10 pt-6 sm:px-6 lg:px-10">
            <div
                v-if="$page.props.flash?.success"
                class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
            >
                {{ $page.props.flash.success }}
            </div>

            <div class="mb-8 sm:mb-10">
                <span
                    class="mb-2 block text-xs font-bold uppercase tracking-widest text-folio-primary"
                    >Gestão de catálogo</span
                >
                <h1
                    class="font-headline text-3xl font-extrabold tracking-tight text-folio-on-surface sm:text-4xl"
                >
                    Livro de estoque
                </h1>
            </div>

            <div class="relative mb-8 max-w-md">
                <span
                    class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-lg text-folio-outline"
                    >search</span
                >
                <input
                    type="search"
                    disabled
                    class="w-full rounded-full border-0 bg-folio-surface-low py-2 pl-10 pr-4 text-sm text-folio-on-surface placeholder:text-folio-outline/80"
                    placeholder="Pesquisar ISBN, título ou autor…"
                />
            </div>

            <div
                class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200/80"
            >
                <div
                    class="flex flex-wrap items-center justify-between gap-3 border-b border-folio-outline-variant/10 bg-folio-surface-low/60 px-6 py-3"
                >
                    <span
                        class="text-xs font-semibold uppercase tracking-wider text-folio-secondary"
                        >Catálogo</span
                    >
                    <div
                        class="flex flex-wrap items-center gap-2 sm:gap-3"
                    >
                        <label
                            class="text-xs font-medium text-folio-secondary"
                            for="por-pagina"
                            >Por página</label
                        >
                        <select
                            id="por-pagina"
                            class="rounded-lg border-0 bg-white py-1.5 pl-3 pr-8 text-sm font-medium text-folio-on-surface shadow-sm ring-1 ring-folio-outline-variant/30 focus:ring-2 focus:ring-folio-primary/25"
                            :value="livros.per_page"
                            @change="mudarPorPagina"
                        >
                            <option
                                v-for="n in porPaginaOpcoes"
                                :key="n"
                                :value="n"
                            >
                                {{ n }}
                            </option>
                        </select>
                    </div>
                    <p class="text-sm text-folio-on-surface">
                        <span class="font-headline text-lg font-extrabold tabular-nums">{{
                            livros.total
                        }}</span>
                        <span class="text-folio-secondary">
                            {{
                                livros.total === 1
                                    ? ' livro no total'
                                    : ' livros no total'
                            }}
                        </span>
                    </p>
                </div>

                <p
                    v-if="livros.data.length === 0"
                    class="px-6 py-14 text-center text-sm text-folio-secondary"
                >
                    Ainda não há livros cadastrados.
                </p>

                <div
                    v-else
                    class="divide-y divide-folio-outline-variant/10 md:hidden"
                >
                    <div
                        v-for="livro in livros.data"
                        :key="`m-${livro.id}`"
                    >
                        <button
                            type="button"
                            class="flex w-full items-center gap-3 px-4 py-4 text-left transition-colors hover:bg-folio-surface-low/60 active:bg-folio-surface-low"
                            :aria-expanded="livroExpandidoId === livro.id"
                            :aria-controls="`livro-detalhe-${livro.id}`"
                            @click="alternarExpansaoLivro(livro.id)"
                        >
                            <div
                                class="flex h-12 w-9 shrink-0 items-center justify-center rounded bg-gradient-to-br from-folio-primary-container to-folio-secondary text-xs font-bold text-white shadow-sm"
                                aria-hidden="true"
                            >
                                {{ iniciais(livro.titulo) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="font-headline font-bold leading-snug text-folio-on-surface line-clamp-2"
                                >
                                    {{ livro.titulo }}
                                </p>
                                <p class="mt-0.5 truncate text-sm text-folio-secondary">
                                    {{ livro.autor }}
                                </p>
                                <p class="mt-1 text-sm font-bold tabular-nums text-folio-on-surface">
                                    {{ formatarPreco(livro.preco) }}
                                </p>
                            </div>
                            <span
                                class="material-symbols-outlined shrink-0 text-folio-outline transition-transform duration-200"
                                :class="
                                    livroExpandidoId === livro.id
                                        ? 'rotate-90'
                                        : ''
                                "
                                aria-hidden="true"
                                >chevron_right</span
                            >
                        </button>
                        <Transition
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="-translate-y-1 opacity-0"
                            enter-to-class="translate-y-0 opacity-100"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="translate-y-0 opacity-100"
                            leave-to-class="-translate-y-1 opacity-0"
                        >
                            <div
                                v-show="livroExpandidoId === livro.id"
                                :id="`livro-detalhe-${livro.id}`"
                                class="border-t border-folio-outline-variant/10 bg-folio-surface-low/60 px-4 pb-4"
                            >
                                <div
                                    class="max-h-[min(70vh,24rem)] space-y-3 overflow-y-auto pt-3 text-sm"
                                >
                                    <dl class="space-y-3">
                                        <div>
                                            <dt
                                                class="text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                            >
                                                ISBN-13
                                            </dt>
                                            <dd class="mt-0.5 break-all font-mono text-folio-on-surface">
                                                {{ livro.isbn }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt
                                                class="text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                            >
                                                Categoria
                                            </dt>
                                            <dd class="mt-0.5">
                                                <span
                                                    v-if="livro.categoria?.nome"
                                                    class="inline-block rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider"
                                                    :class="chipCategoria(livro)"
                                                >
                                                    {{ livro.categoria.nome }}
                                                </span>
                                                <span v-else class="text-folio-outline">—</span>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt
                                                class="text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                            >
                                                Data de publicação
                                            </dt>
                                            <dd class="mt-0.5 text-folio-on-surface">
                                                {{ formatarData(livro.publicado_em) }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <Link
                                        v-if="podeEditar"
                                        :href="route('livros.edit', livro.id)"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-folio-primary px-4 py-2.5 font-headline text-sm font-bold text-folio-on-primary shadow-sm transition-opacity hover:opacity-95 min-[380px]:flex-none"
                                        @click="alternarExpansaoLivro(livro.id)"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                        Editar
                                    </Link>
                                    <button
                                        type="button"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-folio-outline-variant/30 bg-white px-4 py-2.5 font-headline text-sm font-bold text-folio-on-surface transition-colors hover:bg-folio-surface-high disabled:cursor-not-allowed disabled:opacity-40 min-[380px]:flex-none"
                                        :disabled="!podeExcluir"
                                        @click="abrirModalExcluir(livro)"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>

                <div v-if="livros.data.length > 0" class="hidden overflow-x-auto md:block">
                    <table class="w-full min-w-[56rem] border-collapse text-left">
                        <thead>
                            <tr class="bg-folio-surface-low/80">
                                <th
                                    class="px-6 py-4 font-headline text-[11px] font-extrabold uppercase tracking-wider text-folio-secondary"
                                >
                                    Título e autor
                                </th>
                                <th
                                    class="px-6 py-4 font-headline text-[11px] font-extrabold uppercase tracking-wider text-folio-secondary"
                                >
                                    ISBN-13
                                </th>
                                <th
                                    class="px-6 py-4 font-headline text-[11px] font-extrabold uppercase tracking-wider text-folio-secondary"
                                >
                                    Categoria
                                </th>
                                <th
                                    class="px-6 py-4 font-headline text-[11px] font-extrabold uppercase tracking-wider text-folio-secondary"
                                >
                                    Preço
                                </th>
                                <th
                                    class="px-6 py-4 text-center font-headline text-[11px] font-extrabold uppercase tracking-wider text-folio-secondary"
                                >
                                    Data de pub.
                                </th>
                                <th
                                    class="px-6 py-4 text-right font-headline text-[11px] font-extrabold uppercase tracking-wider text-folio-secondary"
                                >
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="livro in livros.data"
                                :key="livro.id"
                                class="group transition-colors hover:bg-folio-surface-low/60"
                            >
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex h-14 w-10 shrink-0 items-center justify-center rounded bg-gradient-to-br from-folio-primary-container to-folio-secondary text-sm font-bold text-white shadow-sm"
                                            aria-hidden="true"
                                        >
                                            {{ iniciais(livro.titulo) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="font-headline font-bold text-folio-on-surface transition-colors group-hover:text-folio-primary"
                                            >
                                                {{ livro.titulo }}
                                            </p>
                                            <p class="text-sm text-folio-secondary">
                                                {{ livro.autor }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-5 font-mono text-xs text-folio-on-surface-variant"
                                >
                                    {{ livro.isbn }}
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        v-if="livro.categoria?.nome"
                                        class="inline-block rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider"
                                        :class="chipCategoria(livro)"
                                    >
                                        {{ livro.categoria.nome }}
                                    </span>
                                    <span v-else class="text-folio-outline">—</span>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-5 font-bold text-folio-on-surface"
                                >
                                    {{ formatarPreco(livro.preco) }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-5 text-center text-sm text-folio-secondary"
                                >
                                    {{ formatarData(livro.publicado_em) }}
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div
                                        class="flex justify-end gap-1 sm:gap-2"
                                    >
                                        <Link
                                            v-if="podeEditar"
                                            :href="route('livros.edit', livro.id)"
                                            class="rounded-lg p-2 text-folio-secondary transition-all hover:bg-folio-primary-fixed hover:text-folio-primary"
                                            aria-label="Editar livro"
                                        >
                                            <span
                                                class="material-symbols-outlined text-[20px]"
                                                >edit</span
                                            >
                                        </Link>
                                        <span
                                            v-else
                                            class="inline-flex rounded-lg p-2 opacity-40"
                                            aria-hidden="true"
                                        >
                                            <span
                                                class="material-symbols-outlined text-[20px] text-folio-secondary"
                                                >edit</span
                                            >
                                        </span>
                                        <button
                                            type="button"
                                            class="rounded-lg p-2 text-folio-secondary transition-all hover:bg-folio-error-container hover:text-folio-error disabled:cursor-not-allowed disabled:opacity-40"
                                            :disabled="!podeExcluir"
                                            aria-label="Excluir livro"
                                            @click="abrirModalExcluir(livro)"
                                        >
                                            <span
                                                class="material-symbols-outlined text-[20px]"
                                                >delete</span
                                            >
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="
                        livros.total > 0 &&
                        livros.links &&
                        livros.links.length > 3
                    "
                    class="flex flex-col gap-4 border-t border-folio-outline-variant/15 bg-folio-surface-high/40 px-6 py-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <p
                        v-if="intervaloLista"
                        class="text-xs font-medium text-folio-secondary"
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
                        títulos
                    </p>
                    <nav
                        class="flex flex-wrap items-center justify-center gap-1"
                        aria-label="Paginação"
                    >
                        <template
                            v-for="link in livros.links"
                            :key="`${link.label}-${link.url ?? 'current'}`"
                        >
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="inline-flex min-h-8 min-w-8 items-center justify-center rounded-lg px-2 text-sm font-bold transition-colors"
                                :class="
                                    link.active
                                        ? 'bg-folio-primary text-folio-on-primary'
                                        : 'text-folio-secondary hover:bg-folio-surface-high'
                                "
                                preserve-scroll
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
        </div>

        <Link
            v-if="podeCadastrar"
            :href="route('livros.create')"
            class="fixed bottom-8 right-8 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-folio-primary text-folio-on-primary shadow-lg shadow-folio-primary/30 transition-transform hover:scale-105 active:scale-95"
            aria-label="Adicionar novo livro"
        >
            <span
                class="material-symbols-outlined text-2xl"
                style="font-variation-settings: 'FILL' 1"
                >add</span
            >
        </Link>

        <ConfirmDeleteModal
            :show="modalExcluirAberto"
            :titulo-obra="livroParaExcluir?.titulo ?? ''"
            :processing="aExcluir"
            @close="fecharModalExcluir"
            @confirm="executarExclusao"
        />
    </AuthenticatedLayout>
</template>
