<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    provinces: Array,
});

const storageUrl = (path) => (path ? `/storage/${path}` : '');

const destroy = (province) => {
    if (confirm(`Hapus provinsi "${province.name}"? Semua item budaya terkait ikut terhapus.`)) {
        router.delete(route('admin.provinces.destroy', province));
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Kelola Provinsi" />

        <div class="mb-6 flex items-end justify-between">
            <div>
                <p class="font-['Space_Mono'] text-xs uppercase tracking-[0.14em] text-[#d6982e]">Konten</p>
                <h1 class="mt-1 font-['Fraunces'] text-2xl font-semibold text-gray-900">Provinsi ({{ provinces.length }})</h1>
            </div>
            <Link
                :href="route('admin.provinces.create')"
                class="inline-flex items-center gap-2 rounded-full bg-[#d6982e] px-4 py-2 text-sm font-semibold text-[#14283f] hover:bg-[#f0c878]"
            >
                <i class="bi bi-plus-lg"></i> Tambah Provinsi
            </Link>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Provinsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Item Budaya</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="province in provinces" :key="province.id" class="hover:bg-gray-50/60">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <img
                                    v-if="province.hero_image_path"
                                    :src="storageUrl(province.hero_image_path)"
                                    class="h-10 w-10 rounded-lg object-cover"
                                    alt=""
                                />
                                <span v-else class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                    <i class="bi bi-image"></i>
                                </span>
                                <span class="text-sm font-medium text-gray-900">{{ province.name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ province.slug }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center rounded-full bg-[#14283f]/5 px-2.5 py-0.5 text-xs font-medium text-[#14283f]">
                                {{ province.culture_items_count }} item
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a
                                    :href="`/province/${province.slug}`"
                                    target="_blank"
                                    title="Lihat di situs"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                                >
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <Link
                                    :href="route('admin.provinces.edit', province)"
                                    title="Edit"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full text-[#14283f]/60 hover:bg-[#14283f]/10 hover:text-[#14283f]"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </Link>
                                <button
                                    @click="destroy(province)"
                                    title="Hapus"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full text-red-400 hover:bg-red-50 hover:text-red-600"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
