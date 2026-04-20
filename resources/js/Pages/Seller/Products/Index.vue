<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    products: Array,
});
</script>

<template>
    <Head title="Manajemen Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100">Daftar Produk Saku</h2>
                <Link
                    :href="route('seller.products.create')"
                    class="bg-primary-600 text-white px-6 py-3 rounded-2xl text-xs font-black tracking-widest hover:bg-primary-700 transition shadow-lg shadow-primary-500/20 uppercase"
                >
                    + Tambah Produk
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-2xl shadow-primary-500/5 sm:rounded-[40px] border border-gray-100 dark:border-gray-800">
                    <div class="p-8 overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-gray-50 dark:border-gray-800">
                                    <th class="pb-6 pl-4">Produk</th>
                                    <th class="pb-6">Kategori</th>
                                    <th class="pb-6">Harga</th>
                                    <th class="pb-6">Stok</th>
                                    <th class="pb-6 pr-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                <tr v-for="product in products" :key="product.id" class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                                    <td class="py-6 pl-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 overflow-hidden flex-shrink-0">
                                                <img v-if="product.image" :src="product.image" class="w-full h-full object-cover">
                                                <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <span class="font-bold text-gray-900 dark:text-gray-100">{{ product.name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <span class="px-3 py-1 bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 rounded-full text-[10px] font-black uppercase tracking-wider">
                                            {{ product.category.name }}
                                        </span>
                                    </td>
                                    <td class="py-6 font-bold text-gray-900 dark:text-gray-100">
                                        Rp {{ product.price.toLocaleString() }}
                                    </td>
                                    <td class="py-6 font-bold" :class="product.stock < 10 ? 'text-red-500' : 'text-gray-600 dark:text-gray-400'">
                                        {{ product.stock }}
                                    </td>
                                    <td class="py-6 pr-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="route('seller.products.edit', product.id)"
                                                class="p-2 bg-gray-100 hover:bg-primary-500 hover:text-white dark:bg-gray-800 rounded-xl transition-all"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </Link>
                                            <Link
                                                :href="route('seller.products.destroy', product.id)"
                                                method="delete"
                                                as="button"
                                                class="p-2 bg-gray-100 hover:bg-red-500 hover:text-white dark:bg-gray-800 rounded-xl transition-all"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="products.length === 0">
                                    <td colspan="5" class="py-20 text-center">
                                        <p class="text-gray-500">Belum ada produk. Mulai jualan sekarang!</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
