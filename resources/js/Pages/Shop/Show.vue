<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    shop: Object,
});

const page = usePage();
const auth = computed(() => page.props.auth);
const Layout = computed(() => auth.value?.user ? AuthenticatedLayout : GuestLayout);
</script>

<template>
    <Head :title="shop.name" />

    <component :is="Layout">
        <template #header>
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-3xl bg-primary-600 flex items-center justify-center text-white shadow-xl shadow-primary-500/20">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-gray-100">{{ shop.name }}</h2>
                    <p class="text-gray-500 font-medium">{{ shop.address }}</p>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-900 rounded-[40px] p-10 border border-gray-100 dark:border-gray-800 shadow-sm mb-12">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Tentang Toko</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ shop.description }}</p>
                    <div class="mt-6 flex gap-6 text-sm">
                        <div class="flex items-center gap-2 text-gray-500">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ shop.phone }}
                        </div>
                        <div class="flex items-center gap-2 text-gray-500">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z"></path>
                            </svg>
                            Bergabung {{ new Date(shop.created_at).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }) }}
                        </div>
                    </div>
                </div>

                <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 mb-8">Produk Pilihan Toko</h3>
                
                <div v-if="shop.products.length > 0" class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="product in shop.products" :key="product.id" 
                         class="group relative bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-800 hover:border-primary-400 transition-all duration-500 hover:shadow-2xl">
                        
                        <Link :href="route('products.show', product.slug)" class="block aspect-square relative overflow-hidden bg-gray-100 dark:bg-gray-800">
                            <img v-if="product.image" :src="product.image" :alt="product.name" class="object-cover w-full h-full transform group-hover:scale-110 transition duration-700" />
                            <div v-else class="flex items-center justify-center w-full h-full text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </Link>

                        <div class="p-6">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1 leading-tight line-clamp-2">
                                <Link :href="route('products.show', product.slug)" class="hover:text-primary-600 transition">{{ product.name }}</Link>
                            </h4>
                            <p class="text-xl font-black text-primary-600 mt-4">Rp {{ product.price.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-20 bg-white dark:bg-gray-900 rounded-[40px] border border-gray-100 dark:border-gray-800">
                    <p class="text-gray-500">Toko ini belum memiliki produk.</p>
                </div>
            </div>
        </div>
    </component>
</template>
