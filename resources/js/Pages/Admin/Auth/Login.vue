<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("admin.login.post"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-950">
        <div
            class="w-full max-w-md bg-gray-900 rounded-2xl shadow-xl p-8 border border-gray-800"
        >
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white italic tracking-tight">
                    Jordan <span class="text-sky-400">Plastik</span>
                </h1>
                <p class="text-gray-400 mt-2 text-sm">Admin Dashboard</p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Email -->
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-300 mb-1"
                    >
                        Email
                    </label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-sky-400 transition"
                        placeholder="admin@example.com"
                    />
                    <p
                        v-if="form.errors.email"
                        class="text-red-400 text-xs mt-1"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <!-- Password -->
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-300 mb-1"
                    >
                        Password
                    </label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-sky-400 transition"
                        placeholder="••••••••"
                    />
                    <p
                        v-if="form.errors.password"
                        class="text-red-400 text-xs mt-1"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2">
                    <input
                        v-model="form.remember"
                        type="checkbox"
                        id="remember"
                        class="rounded border-gray-600 bg-gray-800 text-sky-400"
                    />
                    <label for="remember" class="text-sm text-gray-400">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-2.5 bg-sky-500 hover:bg-sky-600 disabled:opacity-50 text-white font-semibold rounded-lg transition"
                >
                    {{
                        form.processing ? "Memproses..." : "Masuk sebagai Admin"
                    }}
                </button>
            </form>
        </div>
    </div>
</template>
