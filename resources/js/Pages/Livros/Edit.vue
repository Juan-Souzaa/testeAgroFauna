<script setup>
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();

const props = defineProps({
    livro: {
        type: Object,
        required: true,
    },
    categorias: {
        type: Array,
        required: true,
    },
});

const podeExcluir = computed(() =>
    (page.props.permissions ?? []).includes('books.delete'),
);

const form = useForm({
    titulo: props.livro.titulo,
    autor: props.livro.autor,
    isbn: props.livro.isbn,
    preco: props.livro.preco,
    categoria_id: props.livro.categoria_id,
    publicado_em: props.livro.publicado_em ?? '',
});

const submit = () => {
    form.put(route('livros.update', props.livro.id));
};

const modalExcluirAberto = ref(false);
const aExcluir = ref(false);

function abrirModalExcluir() {
    modalExcluirAberto.value = true;
}

function fecharModalExcluir() {
    if (aExcluir.value) {
        return;
    }
    modalExcluirAberto.value = false;
}

function executarExclusao() {
    aExcluir.value = true;
    router.delete(route('livros.destroy', props.livro.id), {
        onFinish: () => {
            aExcluir.value = false;
            modalExcluirAberto.value = false;
        },
    });
}
</script>

<template>
    <Head title="Editar livro" />

    <AuthenticatedLayout>
        <div class="bg-folio-surface py-8">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <p
                    class="mb-2 text-xs font-bold uppercase tracking-widest text-folio-secondary"
                >
                    Administração de acervo
                </p>
                <h1
                    class="font-headline text-2xl font-bold text-folio-on-surface sm:text-3xl"
                >
                    Editar livro
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-folio-secondary">
                    Atualize os dados do exemplar. Campos obrigatórios são
                    validados ao gravar.
                </p>

                <div
                    class="mt-8 overflow-hidden rounded-2xl border border-folio-outline-variant/15 bg-white shadow-folio"
                >
                    <div
                        class="border-b border-folio-outline-variant/10 bg-folio-surface-low px-6 py-4 sm:px-8"
                    >
                        <h2
                            class="font-headline text-base font-bold text-folio-on-surface"
                        >
                            Dados do livro
                        </h2>
                    </div>

                    <form class="p-6 sm:p-8" @submit.prevent="submit">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                    for="titulo"
                                    >Título da obra</label
                                >
                                <input
                                    id="titulo"
                                    v-model="form.titulo"
                                    type="text"
                                    required
                                    placeholder="Ex.: O império do sol"
                                    class="folio-auth-input px-4"
                                />
                                <InputError :message="form.errors.titulo" />
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                        for="autor"
                                        >Autor(a)</label
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
                                            id="autor"
                                            v-model="form.autor"
                                            type="text"
                                            required
                                            placeholder="Nome completo"
                                            class="folio-auth-input pl-11 pr-4"
                                        />
                                    </div>
                                    <InputError :message="form.errors.autor" />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                        for="isbn"
                                        >ISBN-13</label
                                    >
                                    <div class="group relative">
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                                        >
                                            <span
                                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                                >tag</span
                                            >
                                        </div>
                                        <input
                                            id="isbn"
                                            v-model="form.isbn"
                                            type="text"
                                            required
                                            placeholder="978-XXXXXXXXXXXXX"
                                            class="folio-auth-input pl-11 pr-4"
                                        />
                                    </div>
                                    <InputError :message="form.errors.isbn" />
                                </div>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                        for="preco"
                                        >Preço (R$)</label
                                    >
                                    <div class="group relative">
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                                        >
                                            <span
                                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                                >payments</span
                                            >
                                        </div>
                                        <input
                                            id="preco"
                                            v-model="form.preco"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            required
                                            placeholder="0,00"
                                            class="folio-auth-input pl-11 pr-4"
                                        />
                                    </div>
                                    <InputError :message="form.errors.preco" />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                        for="categoria_id"
                                        >Categoria</label
                                    >
                                    <div class="group relative">
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 z-10 flex items-center pl-3.5"
                                        >
                                            <span
                                                class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                                >category</span
                                            >
                                        </div>
                                        <select
                                            id="categoria_id"
                                            v-model="form.categoria_id"
                                            required
                                            class="folio-auth-input cursor-pointer appearance-none pl-11 pr-10"
                                        >
                                            <option disabled value="">
                                                Selecione uma categoria
                                            </option>
                                            <option
                                                v-for="c in categorias"
                                                :key="c.id"
                                                :value="c.id"
                                            >
                                                {{ c.nome }}
                                            </option>
                                        </select>
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                                        >
                                            <span
                                                class="material-symbols-outlined text-folio-outline-variant"
                                                >expand_more</span
                                            >
                                        </div>
                                    </div>
                                    <InputError
                                        :message="form.errors.categoria_id"
                                    />
                                </div>
                            </div>

                            <div class="max-w-md space-y-2">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wider text-folio-secondary"
                                    for="publicado_em"
                                    >Data de publicação</label
                                >
                                <div class="group relative">
                                    <div
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                                    >
                                        <span
                                            class="material-symbols-outlined text-folio-outline-variant group-focus-within:text-folio-primary"
                                            >calendar_month</span
                                        >
                                    </div>
                                    <input
                                        id="publicado_em"
                                        v-model="form.publicado_em"
                                        type="date"
                                        class="folio-auth-input pl-11 pr-4"
                                    />
                                </div>
                                <InputError
                                    :message="form.errors.publicado_em"
                                />
                            </div>
                        </div>

                        <div
                            class="mt-10 flex flex-col-reverse gap-3 border-t border-folio-outline-variant/10 pt-8 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <button
                                v-if="podeExcluir"
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-6 py-3.5 font-headline text-sm font-bold text-red-800 transition-colors hover:bg-red-100 disabled:opacity-60"
                                :disabled="form.processing || aExcluir"
                                @click="abrirModalExcluir"
                            >
                                <span
                                    class="material-symbols-outlined mr-1 text-lg"
                                    >delete</span
                                >
                                Excluir livro
                            </button>
                            <span v-else class="hidden sm:block" />

                            <div
                                class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"
                            >
                                <Link
                                    :href="route('livros.index')"
                                    class="inline-flex items-center justify-center rounded-xl border border-folio-outline-variant/30 bg-folio-surface-low px-6 py-3.5 text-center font-headline text-sm font-bold text-folio-on-surface transition-colors hover:bg-folio-surface-high"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container px-8 py-3.5 font-headline text-sm font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all hover:shadow-xl disabled:opacity-60"
                                    :disabled="form.processing"
                                >
                                    <span
                                        class="material-symbols-outlined text-lg"
                                        >save</span
                                    >
                                    Guardar alterações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <ConfirmDeleteModal
            :show="modalExcluirAberto"
            :titulo-obra="livro.titulo"
            :processing="aExcluir"
            @close="fecharModalExcluir"
            @confirm="executarExclusao"
        />
    </AuthenticatedLayout>
</template>
