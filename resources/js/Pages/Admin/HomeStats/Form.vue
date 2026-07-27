<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    homeStat: Object,
});

const form = useForm({
    label: props.homeStat.label,
    value: props.homeStat.value,
});

const submit = () => {
    form.put(route('admin.home-stats.update', props.homeStat.id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Edit Statistik" />

        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Statistik — {{ homeStat.label }}</h1>
            <Link :href="route('admin.home-stats.index')" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</Link>
        </div>

        <form @submit.prevent="submit" class="max-w-lg space-y-4 rounded-lg bg-white p-6 shadow">
            <div>
                <InputLabel for="label" value="Label" />
                <TextInput id="label" v-model="form.label" class="mt-1 block w-full" required />
                <InputError :message="form.errors.label" class="mt-1" />
            </div>
            <div>
                <InputLabel for="value" value="Nilai (mis. 17,504 atau 38)" />
                <TextInput id="value" v-model="form.value" class="mt-1 block w-full" required />
                <InputError :message="form.errors.value" class="mt-1" />
            </div>
            <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>
        </form>
    </AdminLayout>
</template>
