<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Daftar Akun" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Buat Akun Baru</h2>
            <p class="text-sm text-gray-500 mt-2">Daftar sekarang untuk mulai berbelanja produk berkualitas</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="name" value="Nama Lengkap" class="text-gray-700 dark:text-gray-300 font-semibold" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full border-gray-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl"
                    v-model="form.name"
                    required
                    autofocus
                    placeholder="Masukkan nama lengkap"
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-gray-700 dark:text-gray-300 font-semibold" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full border-gray-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl"
                    v-model="form.email"
                    required
                    placeholder="nama@email.com"
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" class="text-gray-700 dark:text-gray-300 font-semibold" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full border-gray-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl"
                    v-model="form.password"
                    required
                    placeholder="Minimal 8 karakter"
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Konfirmasi Password"
                    class="text-gray-700 dark:text-gray-300 font-semibold"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full border-gray-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl"
                    v-model="form.password_confirmation"
                    required
                    placeholder="Ulangi password"
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full justify-center py-4 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 rounded-xl text-sm font-bold tracking-widest transition-all shadow-lg shadow-primary-500/20"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    DAFTAR SEKARANG
                </PrimaryButton>
            </div>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Sudah punya akun? 
                    <Link :href="route('login')" class="text-primary-600 hover:text-primary-700 font-bold">
                        Masuk di sini
                    </Link>
                </p>
            </div>
        </form>
    </AuthLayout>
</template>
