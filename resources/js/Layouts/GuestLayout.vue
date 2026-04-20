<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
            <nav
                class="border-b border-gray-100 bg-white/80 backdrop-blur-md sticky top-0 z-50 dark:border-gray-800 dark:bg-gray-900/80"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-20 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link
                                    :href="route('home')"
                                    class="flex items-center gap-2 group"
                                >
                                    <ApplicationLogo
                                        class="block h-10 w-auto fill-current text-primary-600 transition-transform group-hover:scale-110 duration-300"
                                    />
                                    <span
                                        class="text-xl font-bold text-gray-900 dark:text-gray-100 italic tracking-tight hidden sm:block"
                                    >
                                        Jordan
                                        <span class="text-sky-400"
                                            >Plastik</span
                                        >
                                    </span>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <!-- Produk Dropdown -->
                                <div class="relative">
                                    <Dropdown align="left" width="48">
                                        <template #trigger>
                                            <button
                                                type="button"
                                                class="inline-flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-primary-600 transition"
                                            >
                                                Produk
                                                <svg
                                                    class="ms-1 h-4 w-4 opacity-50"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </template>

                                        <template #content>
                                            <DropdownLink
                                                :href="
                                                    route('home', {
                                                        category: 'pet',
                                                    })
                                                "
                                            >
                                                PET
                                            </DropdownLink>
                                            <DropdownLink
                                                :href="
                                                    route('home', {
                                                        category: 'hdpe',
                                                    })
                                                "
                                            >
                                                HDPE
                                            </DropdownLink>
                                            <DropdownLink
                                                :href="
                                                    route('home', {
                                                        category: 'pet-toples',
                                                    })
                                                "
                                            >
                                                PET TOPLES
                                            </DropdownLink>
                                            <DropdownLink
                                                :href="
                                                    route('home', {
                                                        category: 'galon',
                                                    })
                                                "
                                            >
                                                GALON
                                            </DropdownLink>
                                            <DropdownLink
                                                :href="
                                                    route('home', {
                                                        category: 'custom',
                                                    })
                                                "
                                            >
                                                CUSTOM ORDER
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                        </div>

                        <div
                            class="hidden sm:ms-6 sm:flex sm:items-center gap-4"
                        >
                            <Link
                                :href="route('login')"
                                class="text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-primary-600 transition"
                                >MASUK</Link
                            >
                            <Link
                                :href="route('register')"
                                class="bg-primary-600 text-white px-5 py-2.5 rounded-xl text-xs font-black tracking-widest hover:bg-primary-700 transition shadow-lg shadow-primary-500/20"
                                >DAFTAR</Link
                            >
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2 px-4">
                        <Link
                            :href="route('home')"
                            class="block py-2 text-base font-medium text-gray-600 dark:text-gray-400"
                            >Produk</Link
                        >
                        <div class="pl-4 space-y-1">
                            <Link
                                :href="route('home', { category: 'pet' })"
                                class="block py-2 text-sm text-gray-600 dark:text-gray-400"
                            >
                                • PET
                            </Link>
                            <Link
                                :href="route('home', { category: 'hdpe' })"
                                class="block py-2 text-sm text-gray-600 dark:text-gray-400"
                            >
                                • HDPE
                            </Link>
                            <Link
                                :href="
                                    route('home', { category: 'pet-toples' })
                                "
                                class="block py-2 text-sm text-gray-600 dark:text-gray-400"
                            >
                                • PET TOPLES
                            </Link>
                            <Link
                                :href="route('home', { category: 'galon' })"
                                class="block py-2 text-sm text-gray-600 dark:text-gray-400"
                            >
                                • GALON
                            </Link>
                            <Link
                                :href="route('home', { category: 'custom' })"
                                class="block py-2 text-sm text-gray-600 dark:text-gray-400"
                            >
                                • CUSTOM ORDER
                            </Link>
                        </div>
                        <div
                            class="border-t border-gray-100 dark:border-gray-800 my-2"
                        ></div>
                        <Link
                            :href="route('login')"
                            class="block py-2 text-base font-medium text-gray-600 dark:text-gray-400"
                            >Masuk</Link
                        >
                        <Link
                            :href="route('register')"
                            class="block py-2 text-base font-medium text-primary-600 font-bold"
                            >Daftar Akun</Link
                        >
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white/50 backdrop-blur-sm border-b border-gray-100 dark:border-gray-800 dark:bg-gray-900/50"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <footer
                class="mt-auto py-12 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 text-center"
            >
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ new Date().getFullYear() }}
                    <span class="font-bold italic"
                        >Marketplace
                        <span class="text-primary-600">Botol</span></span
                    >. All rights reserved.
                </p>
            </footer>
        </div>
    </div>
</template>
