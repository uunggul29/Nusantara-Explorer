<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    teamMember: Object,
});

const isEdit = !!props.teamMember;
const storageUrl = (path) => (path ? `/storage/${path}` : '');

const form = useForm({
    name: props.teamMember?.name ?? '',
    role: props.teamMember?.role ?? '',
    quote: props.teamMember?.quote ?? '',
    photo: null,
});

const submit = () => {
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.team-members.update', props.teamMember.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.team-members.store'), { forceFormData: true });
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="isEdit ? 'Edit Anggota Tim' : 'Tambah Anggota Tim'" />

        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">{{ isEdit ? 'Edit' : 'Tambah' }} Anggota Tim</h1>
            <Link :href="route('admin.team-members.index')" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</Link>
        </div>

        <form @submit.prevent="submit" class="max-w-lg space-y-4 rounded-lg bg-white p-6 shadow">
            <div>
                <InputLabel for="name" value="Nama" />
                <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required />
                <InputError :message="form.errors.name" class="mt-1" />
            </div>
            <div>
                <InputLabel for="role" value="Peran (mis. Leader, Member, Senior Lecturer)" />
                <TextInput id="role" v-model="form.role" class="mt-1 block w-full" required />
                <InputError :message="form.errors.role" class="mt-1" />
            </div>
            <div>
                <InputLabel for="quote" value="Deskripsi" />
                <textarea id="quote" v-model="form.quote" rows="3" class="mt-1 block w-full rounded-md border-gray-300" required></textarea>
                <InputError :message="form.errors.quote" class="mt-1" />
            </div>
            <div>
                <InputLabel for="photo" value="Foto" />
                <img v-if="teamMember?.photo_path" :src="storageUrl(teamMember.photo_path)" class="my-2 h-16 w-16 rounded-full object-cover" />
                <input id="photo" type="file" accept="image/*" class="mt-1 block w-full text-sm" @input="form.photo = $event.target.files[0]" />
                <InputError :message="form.errors.photo" class="mt-1" />
            </div>
            <PrimaryButton :disabled="form.processing">{{ isEdit ? 'Simpan' : 'Tambah' }}</PrimaryButton>
        </form>
    </AdminLayout>
</template>
