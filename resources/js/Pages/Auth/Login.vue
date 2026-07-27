<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-full bg-[#d6982e]/10">
            <i class="bi bi-person-fill text-lg text-[#d6982e]"></i>
        </div>

        <h1 class="text-center font-['Fraunces'] text-lg font-semibold text-[#14283f]">Selamat Datang Kembali</h1>
        <p class="mb-4 mt-1 text-center text-xs text-gray-500">Masuk untuk mengelola konten Nusantara Explorer</p>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-3">
            <div class="flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-3.5 py-2 focus-within:border-[#d6982e]">
                <i class="bi bi-envelope-fill text-sm text-gray-400"></i>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="E-mail Address"
                    class="w-full border-none bg-transparent p-0 text-sm text-gray-700 focus:ring-0"
                />
            </div>
            <InputError class="mt-1" :message="form.errors.email" />

            <div class="flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-3.5 py-2 focus-within:border-[#d6982e]">
                <i class="bi bi-lock-fill text-sm text-gray-400"></i>
                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Password"
                    class="w-full border-none bg-transparent p-0 text-sm text-gray-700 focus:ring-0"
                />
            </div>
            <InputError class="mt-1" :message="form.errors.password" />

            <label class="flex items-center gap-2 pt-1">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <span class="text-sm text-gray-600">Ingat saya</span>
            </label>

            <PrimaryButton
                class="w-full justify-center py-2.5 text-sm"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Login
            </PrimaryButton>
        </form>
    </GuestLayout>
</template>
