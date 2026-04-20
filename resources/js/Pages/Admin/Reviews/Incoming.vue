<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    reviews: Object,
});

const selectedReview = ref(null);
const showModal = ref(false);

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

const openDetailModal = (review) => {
    selectedReview.value = review;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedReview.value = null;
};

const approveReview = (reviewId) => {
    if (confirm('Apakah Anda yakin ingin mengapprove ulasan ini?')) {
        const form = useForm({});
        form.patch(route('admin.reviews.approve', reviewId), {
            onSuccess: () => {
                closeModal();
            }
        });
    }
};
</script>

<template>
    <Head title="Ulasan Masuk" />

    <AdminLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Ulasan Masuk</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Ulasan produk yang perlu dicek</p>
            </div>

            <div v-if="reviews.data.length > 0" class="space-y-4">
                <div v-for="review in reviews.data" :key="review.id" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-gray-900 dark:text-gray-100">{{ review.user?.name || 'Unknown' }}</span>
                                <div class="flex items-center gap-0.5">
                                    <span v-for="i in 5" :key="i" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'">★</span>
                                </div>
                                <span class="text-sm text-gray-500">({{ review.rating }} / 5)</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ review.comment }}</p>
                            <p class="text-xs text-gray-500 mt-2">Produk: {{ review.product?.name }}</p>
                        </div>
                        <span :class="getStatusColor(review.status)" class="px-3 py-1 rounded-full text-xs font-semibold uppercase flex-shrink-0">
                            {{ getStatusLabel(review.status) }}
                        </span>
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button @click="openDetailModal(review)" class="text-primary-600 hover:text-primary-800 font-semibold text-sm">
                            Detail Ulasan
                        </button>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="reviews.links" class="flex gap-2">
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

            <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <p class="mt-4 text-gray-500 dark:text-gray-400">Belum ada ulasan masuk</p>
            </div>

            <!-- Detail Modal -->
            <div v-if="showModal && selectedReview" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Detail Ulasan</h3>
                        <button @click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-4">
                        <!-- Pelanggan -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Pelanggan</p>
                            <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ selectedReview.user?.name || 'Unknown' }}</p>
                        </div>

                        <!-- Produk -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Produk</p>
                            <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ selectedReview.product?.name || '-' }}</p>
                        </div>

                        <!-- Rating -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Rating</p>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-0.5">
                                    <span v-for="i in 5" :key="i" :class="i <= selectedReview.rating ? 'text-yellow-400' : 'text-gray-300'" class="text-lg">★</span>
                                </div>
                                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ selectedReview.rating }} / 5</span>
                            </div>
                        </div>

                        <!-- Komentar -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Komentar</p>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ selectedReview.comment }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Status</p>
                            <span :class="getStatusColor(selectedReview.status)" class="px-3 py-1 rounded-full text-xs font-semibold uppercase inline-block">
                                {{ getStatusLabel(selectedReview.status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                        <button @click="approveReview(selectedReview.id)" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                            Approve
                        </button>
                        <button @click="closeModal" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
