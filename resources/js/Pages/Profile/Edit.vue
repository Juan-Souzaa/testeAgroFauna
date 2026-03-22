<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Perfil" />

    <AuthenticatedLayout>
        <div class="bg-folio-surface pb-12 pt-6 sm:pt-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <p
                    class="mb-2 text-xs font-bold uppercase tracking-widest text-folio-secondary"
                >
                    Conta
                </p>
                <h1
                    class="font-headline text-3xl font-extrabold tracking-tight text-folio-on-surface sm:text-4xl"
                >
                    Perfil e segurança
                </h1>
                <p class="mt-2 max-w-xl text-sm leading-relaxed text-folio-secondary">
                    Atualize seus dados, a senha ou exclua a conta. As mudanças
                    são aplicadas ao salvar.
                </p>

                <div class="mt-10 space-y-8">
                    <div
                        class="overflow-hidden rounded-2xl border border-folio-outline-variant/15 bg-white shadow-folio"
                    >
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>

                    <div
                        class="overflow-hidden rounded-2xl border border-folio-outline-variant/15 bg-white shadow-folio"
                    >
                        <UpdatePasswordForm />
                    </div>

                    <div
                        class="overflow-hidden rounded-2xl border border-folio-outline-variant/15 bg-white shadow-folio"
                    >
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
