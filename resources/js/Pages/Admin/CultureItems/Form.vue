<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    province: Object,
    cultureItem: Object,
});

const isEdit = !!props.cultureItem;
const storageUrl = (path) => (path ? `/storage/${path}` : '');

const form = useForm({
    category: props.cultureItem?.category ?? 'artifact',
    title: props.cultureItem?.title ?? '',
    image: null,
});

const submit = () => {
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.culture-items.update', props.cultureItem.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.provinces.culture-items.store', props.province.id), { forceFormData: true });
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="isEdit ? 'Edit Item Budaya' : 'Tambah Item Budaya'" />

        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">
                {{ isEdit ? 'Edit' : 'Tambah' }} Item Budaya — {{ province.name }}
            </h1>
            <Link :href="route('admin.provinces.edit', province)" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</Link>
        </div>

        <form @submit.prevent="submit" class="max-w-lg space-y-4 rounded-lg bg-white p-6 shadow">
            <div>
                <InputLabel for="category" value="Kategori" />
                <select id="category" v-model="form.category" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="artifact">Artifact</option>
                    <option value="food">Food</option>
                    <option value="tradition">Tradition</option>
                    <option value="arts">Arts</option>
                </select>
                <InputError :message="form.errors.category" class="mt-1" />
            </div>

            <div>
                <InputLabel for="title" value="Judul" />
                <TextInput id="title" v-model="form.title" class="mt-1 block w-full" required />
                <InputError :message="form.errors.title" class="mt-1" />
            </div>

            <div>
                <InputLabel for="image" value="Gambar" />
                <img v-if="cultureItem?.image_path" :src="storageUrl(cultureItem.image_path)" class="my-2 h-24 rounded object-cover" />
                <input id="image" type="file" accept="image/*" class="mt-1 block w-full text-sm" @input="form.image = $event.target.files[0]" />
                <InputError :message="form.errors.image" class="mt-1" />
            </div>

            <PrimaryButton :disabled="form.processing">{{ isEdit ? 'Simpan' : 'Tambah' }}</PrimaryButton>
        </form>
    </AdminLayout>
</template>
