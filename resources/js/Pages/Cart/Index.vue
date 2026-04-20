<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    cartItems: Array,
});

const totalPrice = computed(() => {
    return props.cartItems.reduce((acc, item) => acc + (item.product.price * item.quantity), 0);
});

const checkoutForm = useForm({
    address: '',
    phone: '',
    payment_method: 'cod',
});

const removeForm = useForm({});

const removeItem = (id) => {
    if (confirm('Hapus produk ini dari keranjang?')) {
        removeForm.delete(route('cart.destroy', id));
    }
};

const checkout = () => {
    if (!checkoutForm.address || !checkoutForm.phone || !checkoutForm.payment_method) {
        alert('Mohon lengkapi semua data!');
        return;
    }
    
    checkoutForm.post(route('orders.store'));
};
</script>

<template>
    <Head title="Keranjang Belanja" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-primary-800 dark:text-primary-200">
                Keranjang Belanja
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="cartItems.length > 0" class="flex flex-col lg:flex-row gap-8">
                    <!-- Cart Items -->
                    <div class="w-full lg:w-2/3">
                        <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                            <div class="p-6 space-y-6">
                                <div v-for="item in cartItems" :key="item.id" class="flex flex-col sm:flex-row gap-4 border-b pb-6 last:border-0 last:pb-0 dark:border-gray-700">
                                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-lg shrink-0 flex items-center justify-center">
                                        <img v-if="item.product.image" :src="'/storage/' + item.product.image" class="w-full h-full object-cover rounded-lg" />
                                        <svg v-else class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ item.product.name }}</h4>
                                        <p class="text-sm text-gray-500">{{ item.product.volume }} | {{ item.product.material }}</p>
                                        <p class="text-primary-600 font-bold mt-2">Rp {{ item.product.price.toLocaleString('id-ID') }} x {{ item.quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 dark:text-gray-100">Rp {{ (item.product.price * item.quantity).toLocaleString('id-ID') }}</p>
                                        <button @click="removeItem(item.id)" class="text-red-500 hover:text-red-700 text-sm mt-2 transition">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Summary -->
                    <div class="w-full lg:w-1/3">
                        <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg p-6 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 border-b pb-4 dark:border-gray-700">Ringkasan Pesanan</h3>
                            
                            <div class="space-y-4 mb-8">
                                <template v-for="item in cartItems" :key="'summary-'+item.id">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">{{ item.product.name }} ({{ item.quantity }})</span>
                                        <span class="text-gray-900 dark:text-gray-100">Rp {{ (item.product.price * item.quantity).toLocaleString('id-ID') }}</span>
                                    </div>
                                </template>
                                <div class="flex justify-between text-lg font-bold border-t pt-4 dark:border-gray-700">
                                    <span class="text-gray-900 dark:text-gray-100">Total Harga</span>
                                    <span class="text-primary-600">Rp {{ totalPrice.toLocaleString('id-ID') }}</span>
                                </div>
                            </div>

                            <form @submit.prevent="checkout" class="space-y-4">
                                <!-- Alamat Pengiriman -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Pengiriman <span class="text-red-600">*</span></label>
                                    <textarea v-model="checkoutForm.address" required rows="3" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                                </div>

                                <!-- Nomor Telepon -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon / WhatsApp <span class="text-red-600">*</span></label>
                                    <input type="text" v-model="checkoutForm.phone" required class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" placeholder="Contoh: 081234567890" />
                                </div>

                                <!-- Pilihan Pembayaran -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Metode Pembayaran <span class="text-red-600">*</span></label>
                                    <div class="space-y-2">
                                        <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition" :class="checkoutForm.payment_method === 'cod' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900' : 'border-gray-300 dark:border-gray-600'">
                                            <input 
                                                type="radio" 
                                                v-model="checkoutForm.payment_method" 
                                                value="cod" 
                                                class="w-4 h-4 text-primary-600"
                                            />
                                            <div class="ml-3">
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">Bayar di Tempat (COD)</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Bayar saat barang tiba</p>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition" :class="checkoutForm.payment_method === 'transfer' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900' : 'border-gray-300 dark:border-gray-600'">
                                            <input 
                                                type="radio" 
                                                v-model="checkoutForm.payment_method" 
                                                value="transfer" 
                                                class="w-4 h-4 text-primary-600"
                                            />
                                            <div class="ml-3">
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">Transfer Bank</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Transfer sebelum barang dikirim</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" 
                                        :disabled="checkoutForm.processing"
                                        class="w-full px-6 py-4 bg-primary-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 disabled:opacity-50 transition">
                                    {{ checkoutForm.processing ? 'Memproses...' : 'Buat Pesanan Sekarang' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center bg-white dark:bg-gray-800 p-12 rounded-lg shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Keranjang Kosong</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda belum menambahkan produk apa pun ke keranjang.</p>
                    <div class="mt-6">
                        <Link :href="route('home')" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">
                            Lihat Katalog
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


