<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    product: Object,
});

const page = usePage();
const auth = computed(() => page.props.auth);
const Layout = computed(() =>
    auth.value?.user ? AuthenticatedLayout : GuestLayout,
);

const quantity = ref(1);
const selectedImageIndex = ref(0);
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success");

// Review form states
const reviewRating = ref(0);
const reviewComment = ref("");
const hoveredRating = ref(0);

const form = useForm({
    product_id: props.product.id,
    quantity: 1,
});

const reviewForm = useForm({
    rating: 0,
    comment: "",
});

const addToCart = () => {
    form.quantity = quantity.value;
    form.post(route("cart.store"), {
        preserveScroll: true,
        onSuccess: () => {
            // Show toast notification
            toastMessage.value = `${quantity.value} ${quantity.value > 1 ? "produk" : "produk"} berhasil ditambahkan ke keranjang!`;
            toastType.value = "success";
            showToast.value = true;

            // Reset quantity
            quantity.value = 1;

            // Hide toast after 3 seconds
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
    });
};

const submitReview = () => {
    if (reviewRating.value === 0) {
        toastMessage.value = "Silakan pilih rating bintang!";
        toastType.value = "error";
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
        return;
    }

    if (!reviewComment.value.trim()) {
        toastMessage.value = "Silakan tulis ulasan!";
        toastType.value = "error";
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
        return;
    }

    reviewForm.rating = reviewRating.value;
    reviewForm.comment = reviewComment.value;

    reviewForm.post(route("reviews.store", props.product.id), {
        preserveScroll: true,
        onSuccess: () => {
            toastMessage.value = "Ulasan Anda berhasil ditambahkan!.";
            toastType.value = "success";
            showToast.value = true;

            // Reset form
            reviewRating.value = 0;
            hoveredRating.value = 0;
            reviewComment.value = "";

            // Hide toast after 3 seconds
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
        onError: (errors) => {
            toastMessage.value =
                errors.comment || "Terjadi kesalahan saat menambahkan ulasan.";
            toastType.value = "error";
            showToast.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
    });
};

const productImages = computed(() => {
    // Create an array of product images - main image + 3 gallery images
    // For now, we'll use the main image, but in production this would include gallery images
    const images = [props.product.image];
    // Add more images if they exist in the product data
    if (
        props.product.gallery_images &&
        Array.isArray(props.product.gallery_images)
    ) {
        images.push(...props.product.gallery_images.slice(0, 3));
    }
    // Pad with empty slots to show 4 slots
    while (images.length < 4) {
        images.push(null);
    }
    return images;
});

const averageRating = computed(() => {
    if (!props.product.reviews || props.product.reviews.length === 0) return 0;
    const sum = props.product.reviews.reduce(
        (acc, review) => acc + review.rating,
        0,
    );
    return (sum / props.product.reviews.length).toFixed(1);
});

const userReview = computed(() => {
    if (!auth.value?.user || !props.product.reviews) return null;
    return props.product.reviews.find((r) => r.user_id === auth.value.user.id);
});
</script>

<template>
    <Head :title="product.name" />

    <component :is="Layout">
        <template #header>
            <div class="flex items-center gap-4">
                <Link
                    :href="route('home')"
                    class="text-primary-600 hover:text-primary-800"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                </Link>
                <h2
                    class="text-xl font-semibold leading-tight text-primary-800 dark:text-primary-200"
                >
                    Detail Produk
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg"
                >
                    <div class="p-6 flex flex-col lg:flex-row gap-8">
                        <!-- Product Images Section -->
                        <div class="w-full lg:w-1/2">
                            <!-- Main Product Image -->
                            <div
                                class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 mb-4 overflow-hidden"
                            >
                                <img
                                    v-if="productImages[selectedImageIndex]"
                                    :src="
                                        '/storage/' +
                                        productImages[selectedImageIndex]
                                    "
                                    :alt="product.name"
                                    class="object-cover w-full h-full"
                                />
                                <svg
                                    v-else
                                    class="w-24 h-24"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    ></path>
                                </svg>
                            </div>

                            <!-- Product Gallery -->
                            <div class="grid grid-cols-4 gap-3">
                                <button
                                    v-for="(image, index) in productImages"
                                    :key="index"
                                    @click="selectedImageIndex = index"
                                    :class="[
                                        'aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center overflow-hidden transition border-2',
                                        selectedImageIndex === index
                                            ? 'border-primary-600'
                                            : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600',
                                    ]"
                                >
                                    <img
                                        v-if="image"
                                        :src="'/storage/' + image"
                                        :alt="product.name"
                                        class="w-full h-full object-cover"
                                    />
                                    <svg
                                        v-else
                                        class="w-8 h-8 text-gray-300 dark:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="w-full lg:w-1/2">
                            <!-- Nama Produk -->
                            <h1
                                class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2"
                            >
                                {{ product.name }}
                            </h1>

                            <!-- Rating -->
                            <div class="flex items-center gap-4 mb-4">
                                <div class="flex items-center gap-1">
                                    <span
                                        class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                        >{{ averageRating }}</span
                                    >
                                    <div class="flex">
                                        <span
                                            v-for="i in 5"
                                            :key="i"
                                            class="text-yellow-400"
                                            >★</span
                                        >
                                    </div>
                                    <span class="text-sm text-gray-500"
                                        >({{
                                            product.reviews?.length || 0
                                        }}
                                        ulasan)</span
                                    >
                                </div>
                            </div>

                            <!-- Price -->
                            <p class="text-3xl font-bold text-primary-600 mb-2">
                                Rp {{ product.price.toLocaleString("id-ID") }}
                            </p>

                            <!-- Category & Stock Info -->
                            <div
                                class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-100 dark:border-gray-700"
                            >
                                <div>
                                    <span
                                        class="text-xs font-semibold px-2 py-1 bg-primary-100 text-primary-800 rounded inline-block"
                                    >
                                        {{ product.category.name }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm text-gray-500 block"
                                        >Stock (Qty)</span
                                    >
                                    <span
                                        class="font-semibold text-gray-900 dark:text-gray-100"
                                        >{{ product.stock }} pcs</span
                                    >
                                </div>
                            </div>

                            <!-- Product Description -->
                            <div
                                class="mb-6 p-6 bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 rounded-xl border-2 border-cyan-400 dark:border-cyan-600"
                            >
                                <h3
                                    class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-3"
                                >
                                    Deskripsi Produk
                                </h3>
                                <div
                                    v-if="product.description"
                                    class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed"
                                >
                                    {{ product.description }}
                                </div>
                                <p
                                    v-else
                                    class="text-gray-500 dark:text-gray-400 italic"
                                >
                                    Tidak ada deskripsi untuk produk ini.
                                </p>
                            </div>

                            <!-- Buy Product Section (moved here, integrated with product info) -->
                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700"
                            >
                                <h3
                                    class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4"
                                >
                                    Beli Produk
                                </h3>

                                <div v-if="auth?.user" class="space-y-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex items-center border border-gray-300 dark:border-gray-600 rounded-md"
                                        >
                                            <button
                                                @click="
                                                    quantity > 1
                                                        ? quantity--
                                                        : null
                                                "
                                                class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400"
                                            >
                                                -
                                            </button>
                                            <input
                                                type="number"
                                                v-model="quantity"
                                                min="1"
                                                :max="product.stock"
                                                class="w-16 text-center border-none bg-transparent focus:ring-0 dark:text-gray-100 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            />
                                            <button
                                                @click="
                                                    quantity < product.stock
                                                        ? quantity++
                                                        : null
                                                "
                                                class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400"
                                            >
                                                +
                                            </button>
                                        </div>
                                        <span class="text-sm text-gray-500"
                                            >Maks. {{ product.stock }} pcs</span
                                        >
                                    </div>

                                    <button
                                        @click="addToCart"
                                        :disabled="
                                            form.processing ||
                                            product.stock <= 0
                                        "
                                        class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 disabled:opacity-50 transition"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                            ></path>
                                        </svg>
                                        {{
                                            product.stock > 0
                                                ? "Beli Sekarang / Masuk Keranjang"
                                                : "Stok Habis"
                                        }}
                                    </button>
                                </div>
                                <div
                                    v-else
                                    class="bg-primary-50 dark:bg-primary-900/20 p-4 rounded-lg border border-primary-100 dark:border-primary-800"
                                >
                                    <p
                                        class="text-primary-800 dark:text-primary-200 text-center"
                                    >
                                        Silakan
                                        <Link
                                            :href="route('login')"
                                            class="font-bold underline"
                                            >Login</Link
                                        >
                                        untuk melakukan pembelian.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ulasan User (Reviews) Section -->
                <div
                    class="mt-8 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg"
                >
                    <div class="p-6">
                        <h2
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6"
                        >
                            Ulasan User
                        </h2>

                        <!-- Add Review Form (for logged-in users who haven't reviewed yet) -->
                        <div
                            v-if="auth?.user && !userReview"
                            class="mb-8 p-6 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border-2 border-green-400 dark:border-green-600"
                        >
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4"
                            >
                                Tambah Ulasan Anda
                            </h3>

                            <!-- Star Rating -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
                                    >Rating Bintang</label
                                >
                                <div class="flex gap-2">
                                    <button
                                        v-for="rating in 5"
                                        :key="rating"
                                        @click="reviewRating = rating"
                                        @mouseenter="hoveredRating = rating"
                                        @mouseleave="hoveredRating = 0"
                                        class="transition-transform transform hover:scale-110"
                                    >
                                        <span
                                            :class="[
                                                'text-4xl',
                                                (hoveredRating ||
                                                    reviewRating) >= rating
                                                    ? 'text-yellow-400'
                                                    : 'text-gray-300',
                                            ]"
                                            >★</span
                                        >
                                    </button>
                                </div>
                                <p
                                    v-if="reviewRating > 0"
                                    class="text-sm text-gray-600 dark:text-gray-400 mt-2"
                                >
                                    {{ reviewRating }} dari 5 bintang
                                </p>
                            </div>

                            <!-- Review Comment -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
                                    >Tuliskan Ulasan Anda</label
                                >
                                <textarea
                                    v-model="reviewComment"
                                    placeholder="Bagikan pengalaman Anda dengan produk ini..."
                                    maxlength="1000"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100 resize-none"
                                    rows="4"
                                ></textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ reviewComment.length }}/1000 karakter
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <button
                                @click="submitReview"
                                :disabled="reviewForm.processing"
                                class="w-full px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 active:bg-green-800 disabled:opacity-50 transition flex items-center justify-center gap-2"
                            >
                                <svg
                                    v-if="!reviewForm.processing"
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                    ></path>
                                </svg>
                                <span v-if="reviewForm.processing"
                                    >Mengirim...</span
                                >
                                <span v-else>Kirim Ulasan</span>
                            </button>
                        </div>

                        <!-- Show message if user already reviewed -->
                        <div
                            v-if="auth?.user && userReview"
                            class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800"
                        >
                            <p class="text-blue-800 dark:text-blue-200 text-sm">
                                ✓ Anda sudah memberikan ulasan untuk produk ini.
                            </p>
                        </div>

                        <!-- Display Reviews List -->
                        <div
                            v-if="
                                !product.reviews || product.reviews.length === 0
                            "
                            class="text-center py-8"
                        >
                            <p class="text-gray-500 dark:text-gray-400">
                                Belum ada ulasan untuk produk ini.
                            </p>
                        </div>

                        <div v-else class="space-y-6">
                            <div
                                v-for="review in product.reviews"
                                :key="review.id"
                                class="border-b border-gray-100 dark:border-gray-700 pb-6 last:border-b-0"
                            >
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 flex-shrink-0"
                                    >
                                        {{
                                            review.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="flex items-center justify-between mb-1"
                                        >
                                            <h4
                                                class="font-semibold text-gray-900 dark:text-gray-100"
                                            >
                                                {{ review.user.name }}
                                            </h4>
                                            <span
                                                class="text-xs text-gray-500"
                                                >{{
                                                    new Date(
                                                        review.created_at,
                                                    ).toLocaleDateString(
                                                        "id-ID",
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex items-center gap-2 mb-2"
                                        >
                                            <div class="flex">
                                                <span
                                                    v-for="i in 5"
                                                    :key="i"
                                                    :class="
                                                        i <= review.rating
                                                            ? 'text-yellow-400'
                                                            : 'text-gray-300'
                                                    "
                                                    >★</span
                                                >
                                            </div>
                                            <span
                                                class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                                >{{ review.rating }}/5</span
                                            >
                                        </div>
                                        <p
                                            class="text-gray-600 dark:text-gray-400"
                                        >
                                            {{ review.comment }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="transform translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-2 opacity-0"
        >
            <div v-if="showToast" class="fixed bottom-8 right-8 z-50">
                <div
                    :class="[
                        'text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 min-w-[300px]',
                        toastType === 'success' ? 'bg-green-600' : 'bg-red-600',
                    ]"
                >
                    <svg
                        v-if="toastType === 'success'"
                        class="w-6 h-6 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    <svg
                        v-else
                        class="w-6 h-6 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4v2m0 4v2M12 5a7 7 0 110 14 7 7 0 010-14z"
                        ></path>
                    </svg>
                    <p class="font-semibold">{{ toastMessage }}</p>
                </div>
            </div>
        </Transition>
    </component>
</template>
