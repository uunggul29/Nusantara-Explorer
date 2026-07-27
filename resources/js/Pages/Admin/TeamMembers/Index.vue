<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    teamMembers: Array,
});

const storageUrl = (path) => (path ? `/storage/${path}` : '');

const destroy = (member) => {
    if (confirm(`Hapus anggota tim "${member.name}"?`)) {
        router.delete(route('admin.team-members.destroy', member));
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Kelola Tim" />

        <div class="mb-6 flex items-end justify-between">
            <div>
                <p class="font-['Space_Mono'] text-xs uppercase tracking-[0.14em] text-[#d6982e]">Konten</p>
                <h1 class="mt-1 font-['Fraunces'] text-2xl font-semibold text-gray-900">Anggota Tim ({{ teamMembers.length }})</h1>
            </div>
            <Link
                :href="route('admin.team-members.create')"
                class="inline-flex items-center gap-2 rounded-full bg-[#d6982e] px-4 py-2 text-sm font-semibold text-[#14283f] hover:bg-[#f0c878]"
            >
                <i class="bi bi-plus-lg"></i> Tambah Anggota
            </Link>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="member in teamMembers" :key="member.id" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <img :src="storageUrl(member.photo_path)" class="h-12 w-12 rounded-full object-cover" alt="" />
                        <div>
                            <div class="font-medium text-gray-900">{{ member.name }}</div>
                            <div class="text-sm text-gray-500">{{ member.role }}</div>
                        </div>
                    </div>
                    <div class="flex shrink-0 items-center gap-1">
                        <Link
                            :href="route('admin.team-members.edit', member)"
                            title="Edit"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-[#14283f]/60 hover:bg-[#14283f]/10 hover:text-[#14283f]"
                        >
                            <i class="bi bi-pencil-square"></i>
                        </Link>
                        <button
                            @click="destroy(member)"
                            title="Hapus"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-red-400 hover:bg-red-50 hover:text-red-600"
                        >
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <p class="mt-3 text-sm text-gray-600">{{ member.quote }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
