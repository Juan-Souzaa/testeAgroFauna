<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    categorias: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    titulo: '',
    autor: '',
    isbn: '',
    preco: '',
    categoria_id: '',
    publicado_em: '',
});

const submit = () => {
    form.post(route('livros.store'));
};
</script>

<template>
    <Head title="Cadastrar livro" />

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
                    Cadastrar novo livro
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-folio-secondary">
                    Registe os dados técnicos e comerciais do exemplar. Campos
                    obrigatórios estão assinalados na validação ao gravar.
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

                            <div
                                class="grid gap-6 sm:grid-cols-2"
                            >
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

                            <div
                                class="grid gap-6 sm:grid-cols-2"
                            >
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
                                                :value="String(c.id)"
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
                            class="mt-10 flex flex-col-reverse gap-3 border-t border-folio-outline-variant/10 pt-8 sm:flex-row sm:justify-end"
                        >
                            <Link
                                :href="route('dashboard')"
                                class="inline-flex items-center justify-center rounded-xl border border-folio-outline-variant/30 bg-folio-surface-low px-6 py-3.5 text-center font-headline text-sm font-bold text-folio-on-surface transition-colors hover:bg-folio-surface-high"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-folio-primary to-folio-primary-container px-8 py-3.5 font-headline text-sm font-bold text-folio-on-primary shadow-lg shadow-folio-primary/20 transition-all hover:shadow-xl disabled:opacity-60"
                                :disabled="form.processing"
                            >
                                <span class="material-symbols-outlined text-lg"
                                    >save</span
                                >
                                Salvar livro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
