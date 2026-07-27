<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import RichTextEditor from '@/Components/RichTextEditor.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    province: Object,
});

const isEdit = !!props.province;
const storageUrl = (path) => (path ? `/storage/${path}` : '');

const form = useForm({
    slug: props.province?.slug ?? '',
    name: props.province?.name ?? '',
    display_name_html: props.province?.display_name_html ?? '',
    capital_city: props.province?.capital_city ?? '',
    region_group: props.province?.region_group ?? '',
    map_lat: props.province?.map_lat ?? '',
    map_lng: props.province?.map_lng ?? '',
    mini_game_embed_id: props.province?.mini_game_embed_id ?? '',
    about_intro_html: props.province?.about_intro_html ?? '',
    history_article_html: props.province?.history_article_html ?? '',
    hero_image: null,
    history_top_image_1: null,
    history_top_image_2: null,
    history_bottom_image: null,
});

const submit = () => {
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.provinces.update', props.province.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.provinces.store'), { forceFormData: true });
    }
};

const categories = [
    { key: 'artifact', label: 'Artifact' },
    { key: 'food', label: 'Food' },
    { key: 'tradition', label: 'Tradition' },
    { key: 'arts', label: 'Arts' },
];

const itemsFor = (category) => props.province?.culture_items?.filter((c) => c.category === category) ?? [];

const destroyCultureItem = (item) => {
    if (confirm(`Hapus item "${item.title}"?`)) {
        router.delete(route('admin.culture-items.destroy', item));
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="isEdit ? `Edit ${province.name}` : 'Tambah Provinsi'" />

        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">{{ isEdit ? `Edit ${province.name}` : 'Tambah Provinsi' }}</h1>
            <Link :href="route('admin.provinces.index')" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</Link>
        </div>

        <form @submit.prevent="submit" class="space-y-6 rounded-lg bg-white p-6 shadow">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="slug" value="Slug (untuk URL, mis. jawa-barat)" />
                    <TextInput id="slug" v-model="form.slug" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.slug" class="mt-1" />
                </div>
                <div>
                    <InputLabel for="name" value="Nama Provinsi" />
                    <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>
                <div>
                    <InputLabel for="capital_city" value="Ibu Kota" />
                    <TextInput id="capital_city" v-model="form.capital_city" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.capital_city" class="mt-1" />
                </div>
                <div>
                    <InputLabel for="display_name_html" value="Nama Tampilan Hero (opsional)" />
                    <TextInput id="display_name_html" v-model="form.display_name_html" class="mt-1 block w-full" />
                    <InputError :message="form.errors.display_name_html" class="mt-1" />
                </div>
                <div>
                    <InputLabel for="map_lat" value="Latitude Peta" />
                    <TextInput id="map_lat" v-model="form.map_lat" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.map_lat" class="mt-1" />
                </div>
                <div>
                    <InputLabel for="map_lng" value="Longitude Peta" />
                    <TextInput id="map_lng" v-model="form.map_lng" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.map_lng" class="mt-1" />
                </div>
                <div>
                    <InputLabel for="mini_game_embed_id" value="Mini Game Embed ID (interacty.me)" />
                    <TextInput id="mini_game_embed_id" v-model="form.mini_game_embed_id" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.mini_game_embed_id" class="mt-1" />
                </div>
            </div>

            <div>
                <InputLabel for="hero_image" value="Gambar Hero" />
                <img v-if="province?.hero_image_path" :src="storageUrl(province.hero_image_path)" class="my-2 h-32 rounded object-cover" />
                <input id="hero_image" type="file" accept="image/*" class="mt-1 block w-full text-sm" @input="form.hero_image = $event.target.files[0]" />
                <InputError :message="form.errors.hero_image" class="mt-1" />
            </div>

            <div>
                <InputLabel value="Deskripsi Singkat (kolom kiri)" />
                <RichTextEditor v-model="form.about_intro_html" class="mt-1" />
                <InputError :message="form.errors.about_intro_html" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div v-for="(field, i) in ['history_top_image_1', 'history_top_image_2']" :key="field">
                    <InputLabel :for="field" :value="`Gambar Sejarah ${i + 1}`" />
                    <img v-if="province?.history_top_images?.[i]" :src="storageUrl(province.history_top_images[i])" class="my-2 h-24 rounded object-cover" />
                    <input :id="field" type="file" accept="image/*" class="mt-1 block w-full text-sm" @input="form[field] = $event.target.files[0]" />
                </div>
            </div>

            <div>
                <InputLabel value="Artikel Sejarah (kolom kanan)" />
                <RichTextEditor v-model="form.history_article_html" class="mt-1" />
                <InputError :message="form.errors.history_article_html" class="mt-1" />
            </div>

            <div>
                <InputLabel for="history_bottom_image" value="Gambar Penutup Artikel (opsional)" />
                <img v-if="province?.history_bottom_image" :src="storageUrl(province.history_bottom_image)" class="my-2 h-24 rounded object-cover" />
                <input id="history_bottom_image" type="file" accept="image/*" class="mt-1 block w-full text-sm" @input="form.history_bottom_image = $event.target.files[0]" />
            </div>

            <div class="flex items-center gap-3">
                <PrimaryButton :disabled="form.processing">{{ isEdit ? 'Simpan Perubahan' : 'Tambah Provinsi' }}</PrimaryButton>
            </div>
        </form>

        <div v-if="isEdit" class="mt-8 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="font-['Fraunces'] text-lg font-semibold text-gray-900">Item Budaya</h2>
                <Link
                    :href="route('admin.provinces.culture-items.create', province)"
                    class="inline-flex items-center gap-2 rounded-full bg-[#d6982e] px-3.5 py-1.5 text-sm font-semibold text-[#14283f] hover:bg-[#f0c878]"
                >
                    <i class="bi bi-plus-lg"></i> Tambah Item
                </Link>
            </div>

            <div v-for="cat in categories" :key="cat.key" class="mb-6">
                <h3 class="mb-2 font-['Space_Mono'] text-xs font-semibold uppercase tracking-wide text-gray-400">
                    {{ cat.label }} ({{ itemsFor(cat.key).length }})
                </h3>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 md:grid-cols-6">
                    <div v-for="item in itemsFor(cat.key)" :key="item.id" class="group relative overflow-hidden rounded-lg border border-gray-100">
                        <img :src="storageUrl(item.image_path)" class="h-20 w-full object-cover" />
                        <div class="p-2 text-center">
                            <div class="truncate text-xs font-medium text-gray-700">{{ item.title }}</div>
                        </div>
                        <div class="absolute right-1 top-1 flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                            <Link
                                :href="route('admin.culture-items.edit', item)"
                                title="Edit"
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-white/90 text-[#14283f] shadow"
                            >
                                <i class="bi bi-pencil-square text-xs"></i>
                            </Link>
                            <button
                                @click="destroyCultureItem(item)"
                                title="Hapus"
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-white/90 text-red-600 shadow"
                            >
                                <i class="bi bi-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
