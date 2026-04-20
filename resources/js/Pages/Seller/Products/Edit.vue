<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
    categories: Array,
});

const form = useForm({
    category_id: props.product.category_id,
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
    stock: props.product.stock,
    material: props.product.material,
    volume: props.product.volume,
    color: props.product.color,
});

const submit = () => {
    form.put(route('seller.products.update', props.product.id));
};
</script>

<template>
    <Head title="Edit Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('seller.products.index')" class="text-gray-400 hover:text-primary-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </Link>
                <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100">Edit Produk</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-900 shadow-2xl shadow-primary-500/5 sm:rounded-[40px] p-10 border border-gray-100 dark:border-gray-800">
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="name" value="Nama Produk" />
                                    <TextInput id="name" type="text" class="mt-1 block w-full rounded-2xl" v-model="form.name" required />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div>
                                    <InputLabel for="category_id" value="Kategori" />
                                    <select
                                        id="category_id"
                                        v-model="form.category_id"
                                        class="mt-1 block w-full rounded-2xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                        required
                                    >
                                        <option value="" disabled>Pilih Kategori</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.category_id" />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel for="price" value="Harga (Rp)" />
                                        <TextInput id="price" type="number" class="mt-1 block w-full rounded-2xl" v-model="form.price" required />
                                        <InputError class="mt-2" :message="form.errors.price" />
                                    </div>
                                    <div>
                                        <InputLabel for="stock" value="Stok" />
                                        <TextInput id="stock" type="number" class="mt-1 block w-full rounded-2xl" v-model="form.stock" required />
                                        <InputError class="mt-2" :message="form.errors.stock" />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="material" value="Bahan" />
                                    <TextInput id="material" type="text" class="mt-1 block w-full rounded-2xl" v-model="form.material" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel for="volume" value="Volume" />
                                        <TextInput id="volume" type="text" class="mt-1 block w-full rounded-2xl" v-model="form.volume" />
                                    </div>
                                    <div>
                                        <InputLabel for="color" value="Warna" />
                                        <TextInput id="color" type="text" class="mt-1 block w-full rounded-2xl" v-model="form.color" />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="description" value="Deskripsi Produk" />
                                    <textarea
                                        id="description"
                                        rows="4"
                                        class="mt-1 block w-full rounded-2xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                        v-model="form.description"
                                        required
                                    ></textarea>
                                    <InputError class="mt-2" :message="form.errors.description" />
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50 dark:border-gray-800">
                            <PrimaryButton
                                class="w-full justify-center py-4 bg-primary-600 hover:bg-primary-700 rounded-2xl text-sm font-black tracking-widest"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                UPDATE PRODUK
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
