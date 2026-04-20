<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const URL = window.URL || window.webkitURL;

const props = defineProps({
    product: Object,
    categories: Array,
});

const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    price: props.product.price,
    stock: props.product.stock,
    description: props.product.description || '',
    image: null,
    gallery_images: [],
});

const previewImage = ref(null);
const previewGallery = ref([]);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const handleGalleryImages = (e) => {
    const files = Array.from(e.target.files);
    form.gallery_images = files;
    previewGallery.value = files.map(file => URL.createObjectURL(file));
};

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PATCH'
    })).post(route('admin.products.update', props.product.id), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            console.log('✓ Update berhasil');
        },
        onError: (errors) => {
            console.error('✗ Error:', errors);
        }
    });
};
</script>

<template>
    <Head title="Edit Produk" />

    <AdminLayout>
        <div class="space-y-6 max-w-4xl mx-auto">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.products.index')" class="text-primary-600 hover:text-primary-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Produk</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Perbarui informasi produk Anda</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Kategori -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-primary-600">
                    <label for="category_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Kategori <span class="text-red-600">*</span>
                    </label>
                    <select 
                        id="category_id"
                        v-model="form.category_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required
                    >
                        <option value="">Pilih Kategori</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <div v-if="form.errors.category_id" class="text-red-600 text-sm mt-1">{{ form.errors.category_id }}</div>
                </div>

                <!-- Nama Produk -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-blue-600">
                    <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Nama Produk <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Masukkan nama produk"
                        required
                    />
                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
                </div>

                <!-- Harga & Stok -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-green-600">
                        <label for="price" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Harga Produk <span class="text-red-600">*</span>
                        </label>
                        <input 
                            id="price"
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="0"
                            required
                        />
                        <div v-if="form.errors.price" class="text-red-600 text-sm mt-1">{{ form.errors.price }}</div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-green-600">
                        <label for="stock" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Stok Produk <span class="text-red-600">*</span>
                        </label>
                        <input 
                            id="stock"
                            v-model="form.stock"
                            type="number"
                            min="0"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="0"
                            required
                        />
                        <div v-if="form.errors.stock" class="text-red-600 text-sm mt-1">{{ form.errors.stock }}</div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-purple-600">
                    <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Deskripsi
                    </label>
                    <textarea 
                        id="description"
                        v-model="form.description"
                        rows="5"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Deskripsi produk Anda"
                    ></textarea>
                    <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</div>
                </div>

                <!-- Foto Produk (Thumbnail) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-yellow-600">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">
                        Foto Produk (Thumbnail) <span class="text-red-600">*</span>
                    </label>
                    
                    <!-- Foto saat ini -->
                    <div v-if="product.image && !previewImage" class="mb-4">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Foto saat ini:</p>
                        <img :src="'/storage/' + product.image" :alt="product.name" class="w-full max-w-md h-64 object-cover rounded-lg" />
                    </div>

                    <!-- Upload foto baru -->
                    <input 
                        type="file"
                        accept="image/*"
                        @change="handleImageChange"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 mb-3"
                    />
                    
                    <!-- Preview foto baru -->
                    <div v-if="previewImage" class="mt-3">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Preview foto baru:</p>
                        <img :src="previewImage" alt="Preview" class="w-full max-w-md h-64 object-cover rounded-lg" />
                    </div>

                    <div v-if="!product.image && !previewImage" class="w-full max-w-md h-64 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <p class="text-gray-400">Belum ada foto</p>
                    </div>
                    <div v-if="form.errors.image" class="text-red-600 text-sm mt-1">{{ form.errors.image }}</div>
                </div>

                <!-- Galeri Produk (Multiple) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-pink-600">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">
                        Galeri Produk <span class="text-gray-400 text-xs">(Multiple)</span>
                    </label>

                    <!-- Galeri saat ini -->
                    <div v-if="product.gallery_images && product.gallery_images.length > 0 && previewGallery.length === 0" class="mb-4">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Galeri saat ini ({{ product.gallery_images.length }} foto):</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div v-for="(image, index) in product.gallery_images" :key="index" class="relative">
                                <img :src="'/storage/' + image" :alt="`Gallery ${index + 1}`" class="w-full h-32 object-cover rounded-lg" />
                                <span class="absolute top-1 left-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">{{ index + 1 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Upload galeri baru -->
                    <input 
                        type="file"
                        accept="image/*"
                        multiple
                        @change="handleGalleryImages"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100"
                    />

                    <!-- Preview galeri baru -->
                    <div v-if="previewGallery.length > 0" class="mt-4">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Preview galeri baru ({{ previewGallery.length }} foto):</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div v-for="(image, index) in previewGallery" :key="index" class="relative">
                                <img :src="image" :alt="`Gallery ${index + 1}`" class="w-full h-32 object-cover rounded-lg" />
                                <span class="absolute top-1 left-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">{{ index + 1 }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="form.errors.gallery_images" class="text-red-600 text-sm mt-1">{{ form.errors.gallery_images }}</div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <button 
                        type="submit"
                        :disabled="form.processing"
                        class="flex-1 bg-primary-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-primary-700 transition disabled:opacity-50"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                    <Link :href="route('admin.products.index')" class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-gray-700 transition text-center">
                        Batal
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
