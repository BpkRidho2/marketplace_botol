<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
    recommendations: Array,
    orders: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const auth = computed(() => page.props.auth);
</script>

<template>
    <Head title="Pusat Botol & Jerigen Plastik Berkualitas" />

    <component :is="auth?.user ? AuthenticatedLayout : GuestLayout">
        <!-- Dynamic Hero Section -->
        <div class="relative overflow-hidden bg-primary-900 py-24 sm:py-32">
            <div class="absolute inset-0 opacity-20">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-primary-600 to-transparent"
                ></div>
                <svg
                    class="absolute right-0 top-0 h-full w-1/2 text-primary-500"
                    fill="currentColor"
                    viewBox="0 0 100 100"
                    preserveAspectRatio="none"
                >
                    <path d="M0 0 L100 0 L100 100 Z" />
                </svg>
            </div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-2xl">
                    <h1
                        class="text-4xl font-extrabold tracking-tight text-white sm:text-6xl"
                    >
                        Solusi Kemasan
                        <span class="text-primary-400">Plastik</span> Terbaik
                        Anda
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-primary-100">
                        Menyediakan berbagai macam botol PET, jerigen HDPE, dan
                        kemasan plastik industri lainnya dengan kualitas premium
                        dan harga pabrik.
                    </p>
                    <div class="mt-10 flex items-center gap-x-6"></div>
                </div>
            </div>
        </div>

        <!-- Pesanan Saya Section (For Authenticated Users) -->
        <div
            v-if="auth?.user && orders && orders.length > 0"
            class="py-12 bg-primary-50 dark:bg-primary-900/20 border-b border-primary-100 dark:border-primary-900/30"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2
                                class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                            >
                                Pesanan Saya
                            </h2>
                            <Link
                                :href="route('orders.index')"
                                class="text-primary-600 hover:text-primary-700 font-semibold text-sm underline"
                                >Lihat Semua</Link
                            >
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="border-b dark:border-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300"
                                        >
                                            No. Pesanan
                                        </th>
                                        <th
                                            class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300"
                                        >
                                            Tanggal
                                        </th>
                                        <th
                                            class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300"
                                        >
                                            Total
                                        </th>
                                        <th
                                            class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300"
                                        >
                                            Opsi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y dark:divide-gray-700">
                                    <tr
                                        v-for="order in orders.slice(0, 5)"
                                        :key="order.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50"
                                    >
                                        <td
                                            class="px-4 py-4 font-medium text-primary-600"
                                        >
                                            #{{ order.order_number }}
                                        </td>
                                        <td
                                            class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400"
                                        >
                                            {{
                                                new Date(
                                                    order.created_at,
                                                ).toLocaleDateString("id-ID")
                                            }}
                                        </td>
                                        <td
                                            class="px-4 py-4 font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            Rp
                                            {{
                                                order.total_price.toLocaleString(
                                                    "id-ID",
                                                )
                                            }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                :class="[
                                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                                    order.status === 'pending'
                                                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200'
                                                        : order.status ===
                                                            'confirmed'
                                                          ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200'
                                                          : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                                ]"
                                            >
                                                {{
                                                    order.status === "pending"
                                                        ? "Pending"
                                                        : order.status ===
                                                            "confirmed"
                                                          ? "Confirm"
                                                          : order.status
                                                }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <Link
                                                :href="
                                                    route(
                                                        'orders.show',
                                                        order.id,
                                                    )
                                                "
                                                class="text-primary-600 hover:text-primary-700 font-semibold text-sm"
                                                >Detail</Link
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div
            class="bg-white dark:bg-gray-950 py-12 border-b border-gray-100 dark:border-gray-900"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                    <div
                        class="flex items-center gap-4 p-4 rounded-2xl bg-primary-50 dark:bg-primary-900/10"
                    >
                        <div
                            class="flex-shrink-0 bg-primary-500 p-3 rounded-xl text-white"
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
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <h3
                                class="font-bold text-gray-900 dark:text-gray-100"
                            >
                                Kualitas Premium
                            </h3>
                            <p class="text-xs text-gray-500">
                                Bahan plastik Food Grade & Tebal
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-4 p-4 rounded-2xl bg-primary-50 dark:bg-primary-900/10"
                    >
                        <div
                            class="flex-shrink-0 bg-primary-500 p-3 rounded-xl text-white"
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
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <h3
                                class="font-bold text-gray-900 dark:text-gray-100"
                            >
                                Harga Pabrik
                            </h3>
                            <p class="text-xs text-gray-500">
                                Termurah untuk pembelian grosir
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-4 p-4 rounded-2xl bg-primary-50 dark:bg-primary-900/10"
                    >
                        <div
                            class="flex-shrink-0 bg-primary-500 p-3 rounded-xl text-white"
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
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <h3
                                class="font-bold text-gray-900 dark:text-gray-100"
                            >
                                Pengiriman Cepat
                            </h3>
                            <p class="text-xs text-gray-500">
                                Stok selalu ready kirim hari ini
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendations Section (Collab Filtering) -->
        <div
            v-if="
                recommendations &&
                recommendations.length > 0 &&
                !filters.category
            "
            class="py-16 bg-white dark:bg-gray-950 overflow-hidden"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex items-end justify-between mb-10">
                    <div>
                        <span
                            class="text-primary-600 font-black tracking-widest text-[10px] uppercase bg-primary-50 px-3 py-1 rounded-full"
                            >Algoritma Personalisasi</span
                        >
                        <h2
                            class="text-3xl font-black text-gray-900 dark:text-gray-100 mt-3 italic"
                        >
                            Rekomendasi
                            <span class="text-primary-600">Terbaik</span>
                            Untukmu
                        </h2>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="product in recommendations"
                        :key="'rec-' + product.id"
                        class="group relative bg-gray-50 dark:bg-gray-900/40 rounded-3xl overflow-hidden border border-transparent hover:border-primary-400 transition-all duration-500 hover:shadow-2xl hover:shadow-primary-500/10"
                    >
                        <Link
                            :href="route('products.show', product.slug)"
                            class="block aspect-[4/3] relative overflow-hidden bg-white dark:bg-gray-800"
                        >
                            <img
                                v-if="product.image"
                                :src="'/storage/' + product.image"
                                :alt="product.name"
                                class="object-cover w-full h-full transform group-hover:scale-110 transition duration-700"
                            />
                            <div
                                v-else
                                class="flex items-center justify-center w-full h-full text-gray-200"
                            >
                                <svg
                                    class="w-12 h-12"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                    ></path>
                                </svg>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4"
                            >
                                <span
                                    class="text-white text-[10px] font-bold uppercase tracking-widest"
                                    >Klik untuk detail</span
                                >
                            </div>
                        </Link>

                        <div class="p-5">
                            <div class="flex items-center gap-1.5 mb-2">
                                <svg
                                    class="w-3 h-3 text-primary-500"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                    ></path>
                                </svg>
                                <span
                                    class="text-[10px] font-black text-primary-600 uppercase tracking-tighter"
                                    >Recommended</span
                                >
                            </div>
                            <h4
                                class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-1 leading-tight line-clamp-1 hover:text-primary-600 transition"
                            >
                                <Link
                                    :href="route('products.show', product.slug)"
                                    >{{ product.name }}</Link
                                >
                            </h4>
                            <p
                                class="text-lg font-black text-primary-600 italic mt-3"
                            >
                                Rp {{ product.price.toLocaleString() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div id="katalog" class="py-20 bg-gray-50 dark:bg-gray-950">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-10">
                    <!-- Sidebar Filters -->
                    <div class="w-full md:w-64 flex-shrink-0">
                        <div
                            class="sticky top-24 bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm"
                        >
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-2"
                            >
                                <svg
                                    class="w-5 h-5 text-primary-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                                    ></path>
                                </svg>
                                Kategori
                            </h3>
                            <ul class="space-y-3">
                                <li>
                                    <Link
                                        :href="route('home')"
                                        :class="{
                                            'bg-primary-500 text-white shadow-lg shadow-primary-500/30':
                                                !filters.category,
                                            'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800':
                                                filters.category,
                                        }"
                                        class="flex items-center px-4 py-2.5 rounded-xl transition-all font-semibold text-sm"
                                    >
                                        Semua Produk
                                    </Link>
                                </li>
                                <li
                                    v-for="category in categories"
                                    :key="category.id"
                                >
                                    <Link
                                        :href="
                                            route('home', {
                                                category: category.slug,
                                            })
                                        "
                                        :class="{
                                            'bg-primary-500 text-white shadow-lg shadow-primary-500/30':
                                                filters.category ===
                                                category.slug,
                                            'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800':
                                                filters.category !==
                                                category.slug,
                                        }"
                                        class="flex items-center px-4 py-2.5 rounded-xl transition-all font-semibold text-sm"
                                    >
                                        {{ category.name }}
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Product Grid -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-8">
                            <h2
                                class="text-2xl font-black text-gray-900 dark:text-gray-100"
                            >
                                {{
                                    filters.category
                                        ? categories.find(
                                              (c) =>
                                                  c.slug === filters.category,
                                          )?.name
                                        : "Semua Produk"
                                }}
                            </h2>
                            <p class="text-sm text-gray-500 font-medium">
                                {{ products.data.length }} item tersedia
                            </p>
                        </div>

                        <div
                            v-if="products.data.length > 0"
                            class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3"
                        >
                            <div
                                v-for="product in products.data"
                                :key="product.id"
                                class="group relative bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-800 hover:border-primary-400 dark:hover:border-primary-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-primary-500/10"
                            >
                                <Link
                                    :href="route('products.show', product.slug)"
                                    class="block aspect-square relative overflow-hidden bg-gray-100 dark:bg-gray-800"
                                >
                                    <img
                                        v-if="product.image"
                                        :src="'/storage/' + product.image"
                                        :alt="product.name"
                                        class="object-cover w-full h-full transform group-hover:scale-110 transition duration-700"
                                    />
                                    <div
                                        v-else
                                        class="flex items-center justify-center w-full h-full text-gray-300"
                                    >
                                        <svg
                                            class="w-16 h-16"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300"
                                    >
                                        <div
                                            class="bg-white/90 backdrop-blur p-2 rounded-full shadow-lg text-primary-600 text-xs font-bold px-3"
                                        >
                                            Rp
                                            {{ product.price.toLocaleString() }}
                                        </div>
                                    </div>
                                </Link>

                                <div class="p-6">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 rounded"
                                        >
                                            {{ product.category.name }}
                                        </span>
                                    </div>
                                    <h4
                                        class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1 leading-tight line-clamp-2"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'products.show',
                                                    product.slug,
                                                )
                                            "
                                            class="hover:text-primary-600 transition"
                                            >{{ product.name }}</Link
                                        >
                                    </h4>
                                    <p
                                        class="text-xl font-black text-primary-600 mt-4"
                                    >
                                        Rp {{ product.price.toLocaleString() }}
                                    </p>

                                    <div class="mt-6">
                                        <Link
                                            :href="
                                                route(
                                                    'products.show',
                                                    product.slug,
                                                )
                                            "
                                            class="flex items-center justify-center gap-2 w-full py-3 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-primary-600 hover:text-white transition-all active:scale-95 duration-300"
                                        >
                                            Detail Produk
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                                ></path>
                                            </svg>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-else
                            class="bg-white dark:bg-gray-900 p-20 rounded-[40px] text-center border border-gray-100 dark:border-gray-800 shadow-sm"
                        >
                            <div
                                class="bg-gray-50 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"
                            >
                                <svg
                                    class="w-10 h-10 text-gray-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                    ></path>
                                </svg>
                            </div>
                            <h3
                                class="text-xl font-bold text-gray-900 dark:text-gray-100"
                            >
                                Ups, Produk Kosong!
                            </h3>
                            <p class="text-gray-500 mt-2 max-w-xs mx-auto">
                                Kami tidak dapat menemukan produk di kategori
                                ini saat ini.
                            </p>
                            <Link
                                :href="route('home')"
                                class="mt-8 inline-block text-primary-600 font-bold hover:underline"
                                >Lihat Semua Produk</Link
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
