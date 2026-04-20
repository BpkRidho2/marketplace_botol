<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-50 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-950"
    >
        <div class="flex flex-col items-center">
            <Link
                href="/"
                class="transition-transform hover:scale-105 duration-300"
            >
                <ApplicationLogo
                    class="h-20 w-20 fill-current text-primary-600"
                />
            </Link>
            <h1
                class="mt-4 text-2xl font-bold text-gray-900 dark:text-gray-100 italic tracking-tight"
            >
                Jordan <span class="text-sky-400">Plastik</span>
            </h1>
        </div>

        <div
            class="mt-8 w-full overflow-hidden bg-white px-8 py-10 shadow-2xl shadow-primary-500/10 sm:max-w-md sm:rounded-2xl dark:bg-gray-900 border border-gray-100 dark:border-gray-800"
        >
            <slot />
        </div>
    </div>
</template>
