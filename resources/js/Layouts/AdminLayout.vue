<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const showingSidebar = ref(true);

const menuItems = [
    {
        label: "Dashboard Pesanan",
        icon: "📦",
        children: [
            {
                label: "Semua Pesanan Baru",
                route: "admin.orders.dashboard",
                icon: "✉️",
            },
        ],
    },
    {
        label: "Dashboard Produk",
        icon: "🛍️",
        route: "admin.products.index",
    },
    {
        label: "Dashboard Pesanan",
        icon: "📋",
        children: [
            {
                label: "Pesanan Masuk",
                route: "admin.orders.incoming",
                icon: "➡️",
            },
            {
                label: "Pesanan Dikonfirmasi",
                route: "admin.orders.confirmed",
                icon: "✔️",
            },
            {
                label: "Pesanan Selesai",
                route: "admin.orders.completed",
                icon: "✅",
            },
        ],
    },
    {
        label: "Dashboard Ulasan",
        icon: "⭐",
        children: [
            { label: "Data Ulasan", route: "admin.reviews.index", icon: "📋" },
        ],
    },
    {
        label: "Dashboard Stock Produk",
        icon: "📈",
        route: "admin.stock.index",
    },
];
</script>

<template>
    <div class="flex min-h-screen bg-gray-50 dark:bg-gray-950">
        <!-- Sidebar -->
        <div
            :class="[
                'fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-gray-900 transform transition-transform duration-300 lg:relative lg:translate-x-0 border-r border-gray-100 dark:border-gray-800',
                showingSidebar ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <div class="h-full overflow-y-auto">
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-gray-100 italic tracking-tight"
                    >
                        Jordan <span class="text-sky-400">Plastik</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-2">Admin Dashboard</p>
                </div>

                <!-- Navigation Menu -->
                <nav class="p-6 space-y-2">
                    <div v-for="item in menuItems" :key="item.label">
                        <!-- Menu Item with no children -->
                        <Link
                            v-if="!item.children"
                            :href="route(item.route)"
                            :class="[
                                'flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 rounded-lg transition-all',
                                route().current(item.route)
                                    ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400'
                                    : 'hover:bg-gray-50 dark:hover:bg-gray-800',
                            ]"
                        >
                            <span class="text-lg">{{ item.icon }}</span>
                            {{ item.label }}
                        </Link>

                        <!-- Menu Item with children (dropdown) -->
                        <div v-else class="space-y-2">
                            <div
                                class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300"
                            >
                                <span class="text-lg">{{ item.icon }}</span>
                                {{ item.label }}
                            </div>
                            <div class="pl-8 space-y-1">
                                <Link
                                    v-for="child in item.children"
                                    :key="child.label"
                                    :href="route(child.route)"
                                    :class="[
                                        'flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400 rounded transition-all',
                                        route().current(child.route)
                                            ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400'
                                            : 'hover:bg-gray-50 dark:hover:bg-gray-800',
                                    ]"
                                >
                                    <span class="text-sm">{{
                                        child.icon
                                    }}</span>
                                    {{ child.label }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Overlay for mobile -->
        <div
            v-if="showingSidebar"
            @click="showingSidebar = false"
            class="fixed inset-0 z-30 bg-black/50 lg:hidden"
        ></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <nav
                class="sticky top-0 z-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800"
            >
                <div class="px-4 py-4 flex items-center justify-between">
                    <button
                        @click="showingSidebar = !showingSidebar"
                        class="lg:hidden p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            ></path>
                        </svg>
                    </button>

                    <div class="flex items-center gap-4 ml-auto">
                        <!-- Button Marketplace -->
                        <Link
                            :href="route('home')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg transition"
                        >
                            Marketplace
                        </Link>

                        <!-- User Menu -->
                        <div class="flex items-center gap-2">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600"
                            >
                                {{
                                    page.props.auth.user.name
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </div>
                            <div class="hidden sm:block">
                                <p
                                    class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    {{ page.props.auth.user.name }}
                                </p>
                                <p class="text-xs text-gray-500">Admin</p>
                            </div>
                        </div>

                        <div
                            class="border-l border-gray-100 dark:border-gray-800 pl-4"
                        >
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-sm font-semibold text-red-600 hover:text-red-700 transition"
                            >
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
body {
    overflow-x: hidden;
}
</style>
