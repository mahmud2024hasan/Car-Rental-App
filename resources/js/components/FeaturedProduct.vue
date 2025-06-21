<script setup>
import ProductCard from '@/components/ProductCard.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    cars: Array,
});
</script>

<template>
    <section class="bg-gray-50 py-10">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-semibold text-gray-800">Featured Cars</h2>
                    <p class="mt-1 text-gray-600 text-lg">
                        Discover our top-rated and most popular cars, handpicked just for you. Choose your perfect ride and book instantly for a
                        smooth and comfortable journey.
                    </p>
                </div>
                <Link :href="route('frontend.cars')" class="rounded-md bg-blue-600 px-5 py-2 text-white shadow hover:bg-blue-700">
                    View All Cars
                </Link>
            </div>

            <!-- Grid of Cars -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <ProductCard v-for="car in cars" :key="car.id" :car="car" />
            </div>
        </div>
    </section>
</template>
