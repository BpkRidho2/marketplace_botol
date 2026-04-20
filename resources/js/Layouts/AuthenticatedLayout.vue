<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
const page = usePage();
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
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex sm:items-center"
                            >
                                <!-- Produk Dropdown -->
                                <Dropdown align="left" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent h-20 text-sm font-semibold leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none transition duration-150 ease-in-out"
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
                                            v-for="category in page.props
                                                .categories"
                                            :key="category.id"
                                            :href="
                                                route('home', {
                                                    category: category.slug,
                                                })
                                            "
                                        >
                                            {{ category.name }}
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                                <NavLink
                                    :href="route('cart.index')"
                                    :active="route().current('cart.index')"
                                    class="text-sm font-semibold hover:text-primary-600"
                                >
                                    Keranjang
                                </NavLink>
                                <NavLink
                                    :href="route('orders.index')"
                                    :active="route().current('orders.index')"
                                    class="text-sm font-semibold hover:text-primary-600"
                                >
                                    Pesanan Saya
                                </NavLink>
                            </div>
                        </div>

                        <div
                            class="hidden sm:ms-6 sm:flex sm:items-center gap-4"
                        >
                            <!-- Admin Navigation -->
                            <Link
                                v-if="!$page.props.auth.user.shop"
                                :href="route('shop.create')"
                                class="bg-primary-50 text-primary-600 px-4 py-2 rounded-xl text-xs font-black tracking-widest hover:bg-primary-100 transition"
                            >
                                MULAI JUALAN
                            </Link>
                            <Link
                                v-else
                                :href="route('admin.dashboard')"
                                class="bg-gray-900 text-white px-4 py-2 rounded-xl text-xs font-black tracking-widest hover:bg-gray-800 transition shadow-lg dark:bg-primary-600 dark:hover:bg-primary-700"
                            >
                                ADMIN
                            </Link>
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-xl border border-gray-100 bg-gray-50 px-4 py-2 text-sm font-bold leading-4 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4 opacity-50"
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
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Pengaturan Profil
                                        </DropdownLink>
                                        <div
                                            class="border-t border-gray-100 dark:border-gray-700"
                                        ></div>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="text-red-600 font-semibold"
                                        >
                                            Keluar Akun
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
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
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('home')"
                            :active="route().current('home')"
                        >
                            Produk
                        </ResponsiveNavLink>
                        <div class="pl-4 space-y-1">
                            <ResponsiveNavLink
                                :href="route('home', { category: 'pet' })"
                            >
                                • PET
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('home', { category: 'hdpe' })"
                            >
                                • HDPE
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="
                                    route('home', { category: 'pet-toples' })
                                "
                            >
                                • PET TOPLES
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('home', { category: 'galon' })"
                            >
                                • GALON
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('home', { category: 'custom' })"
                            >
                                • CUSTOM ORDER
                            </ResponsiveNavLink>
                        </div>
                        <ResponsiveNavLink
                            :href="route('cart.index')"
                            :active="route().current('cart.index')"
                        >
                            Keranjang
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('orders.index')"
                            :active="route().current('orders.index')"
                        >
                            Pesanan Saya
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-700"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800 dark:text-gray-200"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profil
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-red-600 font-bold"
                            >
                                Keluar
                            </ResponsiveNavLink>
                        </div>
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

            <!-- Simple Footer to hide Laravel feeling -->
            <footer
                class="mt-auto py-12 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900"
            >
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ new Date().getFullYear() }}
                        <span class="font-bold italic"
                            >Marketplace
                            <span class="text-primary-600">Botol</span></span
                        >. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>
</template>
