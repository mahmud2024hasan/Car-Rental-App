<script setup>
import Pagination from '@/components/Pagination.vue';
import ProductCard from '@/components/ProductCard.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { ref, watch } from 'vue';

const props = defineProps({
    cars: Object,
    filters: Object,
    query: Object,
});

// Define refs
const search = ref(props.query.search || '');
const selectedType = ref(props.query.car_type || '');
const selectedBrand = ref(props.query.brand || '');
const minPrice = ref(props.query.min_price || '');
const maxPrice = ref(props.query.max_price || '');

// Function to apply filters
function applyFilters() {
    router.get(
        route('frontend.cars'),
        {
            search: search.value,
            car_type: selectedType.value,
            brand: selectedBrand.value,
            min_price: minPrice.value,
            max_price: maxPrice.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true, // avoid browser history stack growing
        },
    );
}

// Debounced watcher for search input
watch(
    search,
    debounce(() => {
        applyFilters();
    }, 500),
); // 500ms delay after user stops typing

// Instant watchers for select and price fields
watch([selectedType, selectedBrand, minPrice, maxPrice], applyFilters);
</script>

<template>
    <GuestLayout>
        <!-- Head Section -->
        <Head title="Cars" />

        <!--Content Header Section -->
        <section class="bg-blue-600 px-4 py-8 text-center">
            <div class="container mx-auto">
                <h1 class="mb-2 text-4xl font-extrabold text-white">Browse All Cars — Your Perfect Ride Awaits!</h1>
                <p class="text-xl text-gray-200">Choose from a wide range of vehicles and book in just a few clicks.</p>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container mx-auto p-6">
            <!-- Search and Filters -->
            <div class="mb-2 flex w-full items-center gap-4 py-4">
                <!-- Search Area -->
                <div class="flex w-2/5 items-center justify-center gap-4">
                    <div class="relative w-full">
                        <input
                            v-model="search"
                            type="text"
                            class="h-12 w-full rounded-md border bg-white p-4 shadow-lg focus:ring-1 focus:ring-blue-500 focus:outline-none"
                            placeholder="Search by car name, brand, model"
                            @keyup.enter="submitSearch"
                        />
                        <div class="absolute top-0 right-0 h-full w-12 rounded-r-md bg-gray-200" aria-label="Search">
                            <svg
                                class="absolute top-3.5 right-3 h-5 w-5 fill-current text-gray-500"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                version="1.1"
                                x="0px"
                                y="0px"
                                viewBox="0 0 56.966 56.966"
                                style="enable-background: new 0 0 56.966 56.966"
                                xml:space="preserve"
                            >
                                <path
                                    d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filters by Brand -->
                <select v-model="selectedBrand" class="w-1/5 rounded border px-2 py-2 shadow-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    <option value="">All Brands</option>
                    <option v-for="brand in filters.brands" :key="brand" :value="brand">{{ brand }}</option>
                </select>

                <!-- Filters by Car Type -->
                <select v-model="selectedType" class="w-1/5 rounded border px-2 py-2 shadow-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    <option value="">All Types</option>
                    <option v-for="type in filters.carTypes" :key="type" :value="type">{{ type }}</option>
                </select>

                <!-- Filters by Price -->
                <div class="flex w-1/5 gap-2">
                    <input
                        v-model="minPrice"
                        type="number"
                        placeholder="Min Price"
                        class="w-1/2 rounded border px-2 py-2 shadow-lg focus:ring-1 focus:ring-blue-500 focus:outline-none"
                    />
                    <input
                        v-model="maxPrice"
                        type="number"
                        placeholder="Max Price"
                        class="w-1/2 rounded border px-2 py-2 shadow-lg focus:ring-1 focus:ring-blue-500 focus:outline-none"
                    />
                </div>
            </div>

            <!-- Car Grid -->
            <div v-if="cars.data.length > 0" class="grid grid-cols-4 gap-6">
                <ProductCard v-for="car in cars.data" :key="car.id" :car="car" />
            </div>
            <!-- No Search Results -->
            <div v-else-if="search" class="w-full py-8 text-center text-gray-700">
                😕 No results found for "<strong>{{ search }}</strong
                >"
            </div>

            <!-- No Rentals Available -->
            <div v-else class="py-8 text-center text-gray-500">🚗 No rentals available yet.</div>

            <!-- Pagination -->
            <div v-if="!search">
                <Pagination :links="cars.links" class="mt-6" />
            </div>
        </div>
    </GuestLayout>
</template>
