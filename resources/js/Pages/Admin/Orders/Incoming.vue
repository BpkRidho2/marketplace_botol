<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    orders: Object,
});

const editingStatusId = ref(null);
const processingId = ref(null);
const statusForm = useForm({
    status: ''
});

const getPaymentColor = (method) => {
    const colors = {
        'transfer': 'bg-purple-100 text-purple-800',
        'cod': 'bg-orange-100 text-orange-800',
    };
    return colors[method] || 'bg-gray-100 text-gray-800';
};

const getStatusColor = (status) => {
    const colors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'processing': 'bg-blue-100 text-blue-800',
        'shipped': 'bg-purple-100 text-purple-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-100',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const updateStatus = (orderId, newStatus) => {
    if (confirm(`Ubah status pesanan menjadi "${newStatus}"?`)) {
        processingId.value = orderId;
        statusForm.status = newStatus;
        statusForm.patch(route('admin.orders.updateStatus', orderId), {
            onSuccess: () => {
                editingStatusId.value = null;
                processingId.value = null;
            },
            onError: () => {
                processingId.value = null;
            }
        });
    }
};
</script>

<template>
    <Head title="Pesanan Masuk" />

    <AdminLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Pesanan Masuk</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Pesanan yang baru masuk dan belum dikonfirmasi</p>
            </div>

            <div v-if="orders.data.length > 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">No</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Tanggal</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">No. Pesanan</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Total</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Pembayaran</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="(order, index) in orders.data" :key="order.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ new Date(order.created_at).toLocaleDateString('id-ID') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-primary-600 dark:text-primary-400">
                                    #{{ order.order_number }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">
                                    Rp {{ parseInt(order.total_price).toLocaleString('id-ID') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span :class="getPaymentColor(order.payment_method)" class="px-3 py-1 rounded-full text-xs font-semibold uppercase">
                                        {{ order.payment_method === 'transfer' ? 'Transfer' : 'COD' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 uppercase">
                                        Pending
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2 items-center">
                                        <Link :href="route('orders.show', order.id)" class="text-primary-600 hover:text-primary-800 font-semibold">
                                            Detail
                                        </Link>
                                        <select 
                                            v-if="editingStatusId === order.id"
                                            @change="(e) => updateStatus(order.id, e.target.value)"
                                            class="px-2 py-1 text-xs border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-100"
                                        >
                                            <option value="">Pilih Status</option>
                                            <option value="processing">Processing</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <button 
                                            v-else
                                            @click="editingStatusId = order.id"
                                            class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                                            Ubah Status
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="orders.links" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex gap-2">
                        <Link v-for="link in orders.links" 
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
                <p class="mt-4 text-gray-500 dark:text-gray-400">Tidak ada pesanan masuk</p>
            </div>
        </div>
    </AdminLayout>
</template>
