<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const aberto = defineModel('aberto', { type: Boolean, default: false });

const page = usePage();

const podeVerLivros = computed(() =>
    (page.props.permissions ?? []).includes('books.view'),
);

const podeGerirUsuarios = computed(() =>
    (page.props.permissions ?? []).includes('users.manage'),
);

const podeVerLogs = computed(() =>
    (page.props.permissions ?? []).includes('logs.view'),
);

const linkSidebar = (ativo) => [
    'flex min-w-0 items-center gap-3 rounded-lg px-4 py-3 text-sm font-headline transition-colors duration-200 select-none',
    ativo
        ? 'bg-white font-bold text-folio-primary-container shadow-sm'
        : 'cursor-pointer text-slate-600 hover:bg-slate-200',
];

function fechar() {
    aberto.value = false;
}
</script>

<template>
    <div
        v-show="aberto"
        class="fixed inset-0 z-40 bg-black/30 lg:hidden"
        aria-hidden="true"
        @click="fechar"
    />

    <aside
        :class="[
            'fixed inset-y-0 left-0 z-50 flex h-screen w-64 min-w-0 shrink-0 flex-col gap-y-2 overflow-x-hidden overflow-y-hidden border-r border-slate-200/80 bg-folio-surface-low p-4 transition-transform duration-200 ease-out lg:translate-x-0',
            aberto ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        ]"
    >
        <div class="mb-6 px-4 py-2">
            <Link
                :href="podeVerLivros ? route('livros.index') : route('profile.edit')"
                class="block"
                @click="fechar"
            >
                <span
                    class="text-lg font-extrabold tracking-tighter text-folio-primary-container"
                    >Livraria</span
                >
            </Link>
            <p class="mt-1 text-xs font-medium text-folio-secondary">
                Catálogo 
            </p>
        </div>

        <nav
            class="flex min-h-0 min-w-0 flex-1 flex-col gap-y-1 overflow-y-auto overflow-x-hidden"
        >
            <Link
                v-if="podeVerLivros"
                :href="route('livros.index')"
                :class="
                    linkSidebar(
                        route().current('livros.index'),
                    )
                "
                @click="fechar"
            >
                <span class="material-symbols-outlined shrink-0 text-[22px]">
                    menu_book
                </span>
                <span class="min-w-0 truncate">Livros</span>
            </Link>

            <Link
                v-if="podeGerirUsuarios"
                :href="route('admin.users.index')"
                :class="
                    linkSidebar(
                        route().current('admin.users.index'),
                    )
                "
                @click="fechar"
            >
                <span class="material-symbols-outlined shrink-0 text-[22px]">
                    group
                </span>
                <span class="min-w-0 truncate">Usuários</span>
            </Link>

            <Link
                v-if="podeVerLogs"
                :href="route('admin.logs.index')"
                :class="
                    linkSidebar(
                        route().current('admin.logs.index'),
                    )
                "
                @click="fechar"
            >
                <span class="material-symbols-outlined shrink-0 text-[22px]">
                    history
                </span>
                <span class="min-w-0 truncate">Atividades</span>
            </Link>
        </nav>

        <div class="mt-auto flex min-w-0 flex-col gap-y-1 overflow-x-hidden">
            <Link
                :href="route('profile.edit')"
                :class="linkSidebar(false)"
                @click="fechar"
            >
                <span class="material-symbols-outlined shrink-0 text-[22px]"
                    >person</span
                >
                <span class="min-w-0 truncate">Perfil</span>
            </Link>
            <Link
                :href="route('logout')"
                method="post"
                as="button"
                :class="linkSidebar(false)"
                @click="fechar"
            >
                <span class="material-symbols-outlined shrink-0 text-[22px]"
                    >logout</span
                >
                <span class="min-w-0 truncate">Sair</span>
            </Link>
        </div>
    </aside>
</template>
