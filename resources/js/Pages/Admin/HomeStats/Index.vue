<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    homeStats: Array,
});
</script>

<template>
    <AdminLayout>
        <Head title="Kelola Statistik Home" />

        <p class="font-['Space_Mono'] text-xs uppercase tracking-[0.14em] text-[#d6982e]">Konten</p>
        <h1 class="mt-1 font-['Fraunces'] text-2xl font-semibold text-gray-900">Statistik Home</h1>
        <p class="mt-1 text-sm text-gray-500">Angka-angka yang tampil di beranda situs.</p>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div v-for="stat in homeStats" :key="stat.id" class="relative rounded-xl bg-white p-6 text-center shadow-sm ring-1 ring-gray-100">
                <Link
                    :href="route('admin.home-stats.edit', stat)"
                    title="Edit"
                    class="absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-full text-[#14283f]/40 hover:bg-[#14283f]/10 hover:text-[#14283f]"
                >
                    <i class="bi bi-pencil-square"></i>
                </Link>
                <div class="font-['Fraunces'] text-3xl font-semibold text-gray-900">{{ stat.value }}</div>
                <div class="mt-1 text-sm text-gray-500">{{ stat.label }}</div>
            </div>
        </div>
    </AdminLayout>
</template>
