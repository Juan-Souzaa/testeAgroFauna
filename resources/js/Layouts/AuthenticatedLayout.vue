<script setup>
import Sidebar from '@/Components/Sidebar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const sidebarAberto = ref(false);

const podeVerLivros = computed(() =>
    (page.props.permissions ?? []).includes('books.view'),
);

const podeCadastrarLivro = computed(() =>
    (page.props.permissions ?? []).includes('books.create'),
);

const linkTopo = (ativo) => [
    'font-headline text-sm tracking-tight transition-all',
    ativo
        ? 'border-b-2 border-folio-primary pb-1 font-semibold text-folio-primary'
        : 'cursor-pointer text-slate-500 hover:text-folio-primary',
];

watch(
    () => page.url,
    () => {
        sidebarAberto.value = false;
    },
);
</script>

<template>
    <div
        class="flex min-h-screen bg-folio-surface text-folio-on-surface"
    >
        <Sidebar v-model:aberto="sidebarAberto" />

        <div class="flex min-h-screen min-w-0 flex-1 flex-col">
            <header
                class="sticky top-0 z-30 flex h-16 w-full shrink-0 items-center justify-between gap-4 border-b border-slate-200/60 bg-folio-surface px-4 sm:px-6 lg:px-8"
            >
                <div class="flex min-w-0 flex-1 items-center gap-3 sm:gap-4">
                    <button
                        type="button"
                        class="inline-flex rounded-lg p-2 text-slate-600 hover:bg-slate-200/80 lg:hidden"
                        aria-label="Abrir menu"
                        @click="sidebarAberto = true"
                    >
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h1
                        class="font-headline truncate text-xl font-bold tracking-tighter text-folio-primary"
                    >
                        The Curated Ledger
                    </h1>
                </div>

                <div class="flex shrink-0 items-center gap-4 sm:gap-6">
                    <nav
                        class="hidden items-center gap-6 md:flex md:gap-8"
                    >
                        <Link
                            v-if="podeVerLivros"
                            :href="route('livros.index')"
                            :class="linkTopo(route().current('livros.index'))"
                        >
                            Livros
                        </Link>
                        <Link
                            v-if="podeCadastrarLivro"
                            :href="route('livros.create')"
                            :class="linkTopo(route().current('livros.create'))"
                        >
                            Novo livro
                        </Link>
                    </nav>

                    <div class="flex items-center gap-3 sm:gap-4">
                        <span
                            class="hidden rounded-full p-2 text-slate-500 transition-colors hover:bg-slate-100 sm:inline-flex"
                            title="Notificações"
                            aria-hidden="true"
                        >
                            <span class="material-symbols-outlined text-[22px]">
                                notifications
                            </span>
                        </span>

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    type="button"
                                    class="flex cursor-pointer items-center gap-2 rounded-full p-1 pr-3 transition-colors hover:bg-slate-100"
                                >
                                    <span
                                        class="material-symbols-outlined text-folio-primary"
                                        style="font-variation-settings: 'FILL' 1"
                                        >account_circle</span
                                    >
                                    <span
                                        class="hidden max-w-[8rem] truncate text-sm font-medium text-slate-700 sm:inline"
                                        >{{ $page.props.auth.user.name }}</span
                                    >
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Perfil
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Sair
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <div
                v-if="$slots.header"
                class="border-b border-slate-200/60 bg-white/80 px-4 py-3 sm:px-6 lg:px-8"
            >
                <slot name="header" />
            </div>

            <main class="flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
