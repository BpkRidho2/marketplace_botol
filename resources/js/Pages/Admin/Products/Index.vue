<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    products: Object,
});

const exportToPDF = () => {
    window.location.href = route('admin.products.export');
};
</script>

<template>
    <Head title="Dashboard Produk" />

    <AdminLayout>
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Dashboard Produk</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola daftar produk Anda</p>
                </div>
                <div class="flex gap-3">
                    <button @click="exportToPDF" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                        📥 Export PDF
                    </button>
                    <Link :href="route('admin.products.create')" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-primary-700 transition inline-block">
                        ➕ Tambah Produk
                    </Link>
                </div>
            </div>

            <div v-if="products.data.length > 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">No</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Nama</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Foto Produk</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Kategori</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Harga</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Stock</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="(product, index) in products.data" :key="product.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    {{ product.name }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <img v-if="product.image" :src="'/storage/' + product.image" :alt="product.name" class="h-10 w-10 object-cover rounded" />
                                    <span v-else class="text-gray-400">Tidak ada foto</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ product.category?.name || '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">
                                    Rp {{ parseInt(product.price).toLocaleString('id-ID') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold">
                                    <span :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'">
                                        {{ product.stock }} pcs
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <Link :href="route('admin.products.edit', product.id)" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            ✏️ Edit
                                        </Link>
                                        <Link :href="route('products.show', product.slug)" class="text-primary-600 hover:text-primary-800 font-semibold">
                                            👁️ Lihat
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="products.links" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex gap-2">
                        <Link v-for="link in products.links" 
                              :key="link.label"
                              :href="link.url || '#'"
                              :class="[
                                  'px-3 py-2 text-sm rounded transition',
                                  link.active ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600',
                                  !link.url ? 'opacity-50 cursor-not-allowed' : ''
                              ]"
                              v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="mt-4 text-gray-500 dark:text-gray-400">Belum ada produk</p>
            </div>
        </div>
    </AdminLayout>
</template>
