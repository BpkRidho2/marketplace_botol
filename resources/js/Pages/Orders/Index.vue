<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    orders: Array,
});

const page = usePage();
const showSuccessNotif = ref(false);
const successMessage = ref('');

watch(() => page.props.flash?.success, (newVal) => {
    if (newVal) {
        showSuccessNotif.value = true;
        successMessage.value = newVal;
        setTimeout(() => {
            showSuccessNotif.value = false;
        }, 4000);
    }
});

const getStatusColor = (status) => {
    const colors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'processing': 'bg-blue-100 text-blue-800',
        'shipped': 'bg-purple-100 text-purple-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusLabel = (status) => {
    const labels = {
        'pending': 'Menunggu Konfirmasi',
        'processing': 'Sedang Diproses',
        'shipped': 'Sedang Dikirim',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan',
    };
    return labels[status] || status;
};
</script>

<template>
    <Head title="Riwayat Pesanan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-primary-800 dark:text-primary-200">
                Riwayat Pesanan Saya
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Success Notification -->
                <transition name="slide">
                    <div v-if="showSuccessNotif" class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg shadow-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-bold">{{ successMessage }}</p>
                                <p class="text-sm">Pesanan Anda telah berhasil dibuat dan sedang menunggu konfirmasi.</p>
                            </div>
                        </div>
                        <button @click="showSuccessNotif = false" class="text-green-700 dark:text-green-300 hover:text-green-900 dark:hover:text-green-100">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </transition>

                <div v-if="orders.length > 0" class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="border-b dark:border-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Nomor Pesanan</th>
                                        <th class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Tanggal</th>
                                        <th class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Total</th>
                                        <th class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Metode Bayar</th>
                                        <th class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y dark:divide-gray-700">
                                    <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-4 font-medium text-primary-600">#{{ order.order_number }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ new Date(order.created_at).toLocaleDateString('id-ID') }}
                                        </td>
                                        <td class="px-4 py-4 font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ order.total_price.toLocaleString('id-ID') }}
                                        </td>
                                        <td class="px-4 py-4 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ order.payment_method === 'cod' ? 'COD' : 'Transfer Bank' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span :class="getStatusColor(order.status)" class="px-2 py-1 text-xs font-semibold rounded-full uppercase">
                                                {{ getStatusLabel(order.status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center bg-white dark:bg-gray-800 p-12 rounded-lg shadow-sm">
                    <p class="text-gray-500">Belum ada pesanan yang dibuat.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from {
    transform: translateY(-20px);
    opacity: 0;
}

.slide-leave-to {
    transform: translateY(-20px);
    opacity: 0;
}
</style>
