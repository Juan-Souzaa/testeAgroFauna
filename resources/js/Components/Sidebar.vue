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
    'flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-headline transition-all duration-200 select-none',
    ativo
        ? 'bg-white font-bold text-folio-primary-container shadow-sm'
        : 'cursor-pointer text-slate-600 hover:translate-x-1 hover:bg-slate-200',
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
            'fixed inset-y-0 left-0 z-50 flex w-64 shrink-0 flex-col gap-y-2 border-r border-slate-200/80 bg-folio-surface-low p-4 transition-transform duration-200 ease-out lg:static lg:translate-x-0',
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

        <nav class="flex grow flex-col gap-y-1">
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
                <span class="material-symbols-outlined text-[22px]">
                    menu_book
                </span>
                Livros
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
                <span class="material-symbols-outlined text-[22px]">
                    group
                </span>
                Usuários
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
                <span class="material-symbols-outlined text-[22px]">
                    history
                </span>
                Atividades
            </Link>
        </nav>

        <div class="mt-auto flex flex-col gap-y-1">
            <Link
                :href="route('profile.edit')"
                :class="linkSidebar(false)"
                @click="fechar"
            >
                <span class="material-symbols-outlined text-[22px]">person</span>
                Perfil
            </Link>
            <Link
                :href="route('logout')"
                method="post"
                as="button"
                :class="linkSidebar(false)"
                @click="fechar"
            >
                <span class="material-symbols-outlined text-[22px]">logout</span>
                Sair
            </Link>
        </div>
    </aside>
</template>
