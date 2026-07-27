<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const isEditingProfile = ref(false);
const isEditingPassword = ref(false);
</script>

<template>
    <AdminLayout>
        <Head title="Profil Saya" />

        <p class="font-['Space_Mono'] text-xs uppercase tracking-[0.14em] text-[#d6982e]">Akun</p>
        <h1 class="mt-1 font-['Fraunces'] text-2xl font-semibold text-gray-900">Profil Saya</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola informasi akun dan keamanan login-mu.</p>

        <div class="mt-6 max-w-2xl">
            <!-- Overview card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#d6982e]/10 text-2xl text-[#d6982e]">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <div class="min-w-0">
                        <div class="truncate text-lg font-semibold text-gray-900">{{ user.name }}</div>
                        <div class="truncate text-sm text-gray-500">{{ user.email }}</div>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-2 border-t border-gray-100 pt-5">
                    <button
                        @click="isEditingProfile = true"
                        class="inline-flex items-center gap-1.5 rounded-full bg-[#14283f]/5 px-3.5 py-1.5 text-xs font-semibold text-[#14283f] hover:bg-[#14283f]/10"
                    >
                        <i class="bi bi-pencil-square"></i> Edit Profil
                    </button>
                    <button
                        @click="isEditingPassword = true"
                        class="inline-flex items-center gap-1.5 rounded-full bg-[#14283f]/5 px-3.5 py-1.5 text-xs font-semibold text-[#14283f] hover:bg-[#14283f]/10"
                    >
                        <i class="bi bi-key"></i> Ubah Password
                    </button>
                </div>
            </div>
        </div>

        <!-- Edit profile modal -->
        <Modal :show="isEditingProfile" max-width="md" @close="isEditingProfile = false">
            <div class="p-6">
                <h2 class="mb-6 font-['Fraunces'] text-lg font-semibold text-gray-900">Edit Profil</h2>
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" @saved="isEditingProfile = false" />
            </div>
        </Modal>

        <!-- Change password modal -->
        <Modal :show="isEditingPassword" max-width="md" @close="isEditingPassword = false">
            <div class="p-6">
                <h2 class="mb-6 font-['Fraunces'] text-lg font-semibold text-gray-900">Ubah Password</h2>
                <UpdatePasswordForm @saved="isEditingPassword = false" />
            </div>
        </Modal>
    </AdminLayout>
</template>
