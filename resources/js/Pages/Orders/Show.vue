<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
});
</script>

<template>
    <Head :title="'Pesanan #' + order.order_number" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('orders.index')" class="text-primary-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-primary-800 dark:text-primary-200">
                    Detail Pesanan #{{ order.order_number }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ new Date(order.created_at).toLocaleString('id-ID') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full uppercase">
                                {{ order.status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="font-bold text-gray-900 dark:text-gray-100 mb-4 uppercase text-sm tracking-widest">Daftar Produk</h3>
                        <div class="space-y-4">
                            <div v-for="item in order.items" :key="item.id" class="flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-md flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ item.product.name }}</p>
                                        <p class="text-xs text-gray-500">Rp {{ item.price.toLocaleString() }} x {{ item.quantity }}</p>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900 dark:text-gray-100">Rp {{ (item.price * item.quantity).toLocaleString() }}</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t dark:border-gray-700">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span class="text-gray-700 dark:text-gray-300">Total Pembayaran</span>
                                <span class="text-primary-600">Rp {{ order.total_price.toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t dark:border-gray-700 grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Alamat Pengiriman</h4>
                            <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ order.address }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Kontak</h4>
                            <p class="text-gray-900 dark:text-gray-100">{{ order.phone }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <Link :href="route('home')" class="text-primary-600 hover:underline">
                        Kembali Berbelanja
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
