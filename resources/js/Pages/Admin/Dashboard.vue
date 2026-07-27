<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    counts: Object,
});

const cards = (counts) => [
    { label: 'Provinsi', value: counts.provinces, icon: 'bi-map', route: 'admin.provinces.index' },
    { label: 'Item Budaya', value: counts.cultureItems, icon: 'bi-collection', route: null },
    { label: 'Anggota Tim', value: counts.teamMembers, icon: 'bi-people', route: 'admin.team-members.index' },
];
</script>

<template>
    <AdminLayout>
        <Head title="Admin Dashboard" />

        <p class="font-['Space_Mono'] text-xs uppercase tracking-[0.14em] text-[#d6982e]">Ringkasan</p>
        <h1 class="mt-1 font-['Fraunces'] text-2xl font-semibold text-gray-900">Selamat datang kembali</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola konten Nusantara Explorer dari sini.</p>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <component
                :is="card.route ? Link : 'div'"
                v-for="card in cards(counts)"
                :key="card.label"
                :href="card.route ? route(card.route) : undefined"
                class="group rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md hover:ring-[#d6982e]/30"
            >
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#14283f]/5 text-lg text-[#14283f] group-hover:bg-[#d6982e]/15 group-hover:text-[#d6982e]">
                        <i :class="['bi', card.icon]"></i>
                    </span>
                    <div>
                        <div class="font-['Fraunces'] text-3xl font-semibold text-gray-900">{{ card.value }}</div>
                        <div class="text-sm text-gray-500">{{ card.label }}</div>
                    </div>
                </div>
            </component>
        </div>
    </AdminLayout>
</template>
