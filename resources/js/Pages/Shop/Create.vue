<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    address: '',
    phone: '',
});

const submit = () => {
    form.post(route('shop.store'));
};
</script>

<template>
    <Head title="Buka Toko Botol" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100">Mulai Jualan di Toko Botol</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-2xl shadow-primary-500/10 dark:bg-gray-900 sm:rounded-[32px] p-8 border border-gray-100 dark:border-gray-800">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Informasi Toko</h3>
                        <p class="text-sm text-gray-500 mt-1">Lengkapi data berikut untuk membuka toko Anda sendiri.</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Nama Toko" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full rounded-2xl border-gray-100 focus:ring-primary-500 focus:border-primary-500"
                                v-model="form.name"
                                required
                                autofocus
                                placeholder="Contoh: Toko Botol Berkah"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Deskripsi Toko" />
                            <textarea
                                id="description"
                                class="mt-1 block w-full rounded-2xl border-gray-100 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                v-model="form.description"
                                rows="3"
                                required
                                placeholder="Jelaskan apa yang Anda jual..."
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div>
                            <InputLabel for="address" value="Alamat Toko" />
                            <TextInput
                                id="address"
                                type="text"
                                class="mt-1 block w-full rounded-2xl border-gray-100 focus:ring-primary-500 focus:border-primary-500"
                                v-model="form.address"
                                required
                                placeholder="Jl. Raya No. 123..."
                            />
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>

                        <div>
                            <InputLabel for="phone" value="Nomor WhatsApp" />
                            <TextInput
                                id="phone"
                                type="text"
                                class="mt-1 block w-full rounded-2xl border-gray-100 focus:ring-primary-500 focus:border-primary-500"
                                v-model="form.phone"
                                required
                                placeholder="08123456789"
                            />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>

                        <div class="pt-4">
                            <PrimaryButton
                                class="w-full justify-center py-4 bg-primary-600 hover:bg-primary-700 rounded-2xl text-sm font-black tracking-widest shadow-xl shadow-primary-500/20"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                BUKA TOKO SEKARANG
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
