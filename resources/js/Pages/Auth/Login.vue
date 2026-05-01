<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Masuk Akun" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                Selamat Datang
            </h2>
            <p class="text-sm text-gray-500 mt-2">
                Silakan masuk ke akun Anda untuk berbelanja
            </p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel
                    for="email"
                    value="Email"
                    class="text-gray-700 dark:text-gray-300 font-semibold"
                />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full border-gray-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl"
                    v-model="form.email"
                    required
                    autofocus
                    placeholder="nama@email.com"
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex justify-between items-center">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="text-gray-700 dark:text-gray-300 font-semibold"
                    />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-primary-600 hover:text-primary-700 font-semibold"
                    >
                        Lupa Password?
                    </Link>
                </div>

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full border-gray-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl"
                    v-model="form.password"
                    required
                    placeholder="••••••••"
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <Checkbox
                        name="remember"
                        v-model:checked="form.remember"
                        class="rounded text-primary-600 focus:ring-primary-500"
                    />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                        >Ingat saya</span
                    >
                </label>
            </div>

            <div>
                <PrimaryButton
                    class="w-full justify-center py-3 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 rounded-xl text-sm font-bold tracking-widest transition-all shadow-lg shadow-primary-500/20"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    MASUK SEKARANG
                </PrimaryButton>
            </div>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Belum punya akun?
                    <Link
                        :href="route('register')"
                        class="text-primary-600 hover:text-primary-700 font-bold"
                    >
                        Daftar Gratis
                    </Link>
                </p>
            </div>
            <!-- Tautan menuju halaman login admin -->
            <div
                class="text-center mt-4 pt-4 border-t border-gray-100 dark:border-gray-700"
            >
                <a
                    href="/admin/login"
                    class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                >
                    🔐 Masuk sebagai Admin
                </a>
            </div>
        </form>
    </AuthLayout>
</template>
