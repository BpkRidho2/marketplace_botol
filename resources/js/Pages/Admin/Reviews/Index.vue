<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    reviews: Object,
});

const getStatusColor = (status) => {
    const colors = {
        'perlu_cek': 'bg-yellow-100 text-yellow-800',
        'sudah_upload': 'bg-green-100 text-green-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusLabel = (status) => {
    const labels = {
        'perlu_cek': 'Perlu Cek',
        'sudah_upload': 'Sudah Upload',
    };
    return labels[status] || status;
};

const deleteForm = (reviewId) => {
    if (confirm('Apakah Anda yakin ingin menghapus ulasan ini?')) {
        const form = useForm({});
        form.delete(route('admin.reviews.destroy', reviewId));
    }
};
</script>

<template>
    <Head title="Data Ulasan" />

    <AdminLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Data Ulasan</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola semua ulasan produk</p>
            </div>

            <div v-if="reviews.data.length > 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">No</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Ulasan</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Pelanggan</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Produk</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="(review, index) in reviews.data" :key="review.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-0.5">
                                            <span v-for="i in 5" :key="i" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'" class="text-sm">★</span>
                                        </div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ review.comment }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ review.user?.name || '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ review.product?.name || '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span :class="getStatusColor(review.status)" class="px-3 py-1 rounded-full text-xs font-semibold uppercase">
                                        {{ getStatusLabel(review.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button @click="deleteForm(review.id)" class="text-red-600 hover:text-red-800 font-semibold">
                                        Hapus Ulasan
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="reviews.links" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex gap-2">
                        <Link v-for="link in reviews.links" 
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <p class="mt-4 text-gray-500 dark:text-gray-400">Belum ada data ulasan</p>
            </div>
        </div>
    </AdminLayout>
</template>
