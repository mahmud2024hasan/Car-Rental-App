<script setup>
import FeaturedProduct from '@/components/FeaturedProduct.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import formatDate from '@components/custom/FormatDate.vue';
import FormatNumber from '@components/custom/FormatNumber.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ArrowBigRightDash, BookKey, BookX, CalendarCheck2, CarTaxiFront, Flame, History } from 'lucide-vue-next';

// Props from Laravel
const props = defineProps({
    userName: String,
    rentals: Array,
    topRentedCars: Array,
    featuredCars: Array,
    stats: Object,
});

const page = usePage();

// Navigate to car detail page
function goToCarDetails(carID) {
    router.visit(route('customer.bookings.create', { car_id: carID }));
}

const breadcrumbs = [
    {
        title: 'Customer Dashboard',
        href: '/customer/dashboard',
    },
];
</script>

<template>
    <Head title="Customer Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 bg-gray-100">
            <h1 class="mt-4 px-6 text-lg text-gray-600">
                Hi <span class="font-semibold text-purple-500">{{ userName }}</span
                >, Welcome to Your Dashboard
            </h1>

            <!-- Statistics for cars, rentals and earnings from rentals -->
            <div class="grid grid-cols-5 gap-4 px-4">
                <!-- Total Bookings -->
                <div class="flex h-32 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-2/5 items-center justify-center bg-orange-500 p-4">
                        <BookKey class="h-18 w-18 text-white" />
                    </div>
                    <div class="w-3/5 px-4 text-gray-700">
                        <h3 class="mb-2 text-lg">Total Bookings</h3>
                        <p class="ml-1 text-3xl font-semibold text-orange-500">{{ stats.totalBookings }}</p>
                    </div>
                </div>

                <!-- Active Rentals -->
                <div class="flex h-32 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-2/5 items-center justify-center bg-blue-500 p-4">
                        <CarTaxiFront class="h-20 w-20 text-white" />
                    </div>
                    <div class="px-4 text-gray-700">
                        <h3 class="mb-2 text-lg">Active Rentals</h3>
                        <p class="ml-1 text-3xl font-semibold text-blue-500">{{ stats.activeRentals }}</p>
                    </div>
                </div>

                <!-- Upcoming Rentals -->
                <div class="flex h-32 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-2/5 items-center justify-center bg-purple-600 p-4">
                        <ArrowBigRightDash class="h-20 w-20 text-white" />
                    </div>
                    <div class="px-4 text-gray-700">
                        <h3 class="mb-2 text-lg">Upcoming Rentals</h3>
                        <p class="ml-1 text-3xl font-semibold text-purple-700">{{ stats.upcomingRentals }}</p>
                    </div>
                </div>

                <!-- Completed Rentals -->
                <div class="flex h-32 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-1/3 items-center justify-center bg-green-600 p-4">
                        <CalendarCheck2 class="h-16 w-16 text-white" />
                    </div>
                    <div class="px-4 text-gray-700">
                        <h3 class="mb-2 text-lg">Completed Rentals</h3>
                        <p class="ml-1 text-3xl font-semibold text-green-700">{{ stats.completedRentals }}</p>
                    </div>
                </div>

                <!-- Canceled Rentals -->
                <div class="flex h-32 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-2/5 items-center justify-center bg-red-500 p-4">
                        <BookX class="h-18 w-18 text-white" />
                    </div>
                    <div class="px-4 text-gray-700">
                        <h3 class="mb-2 text-lg">Canceled Rentals</h3>
                        <p class="ml-1 text-3xl font-semibold text-red-500">{{ stats.canceledRentals }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="grid grid-cols-5 gap-4 p-4">
                <!-- Recent Bookings Section -->
                <div class="col-span-3 rounded-lg bg-white p-4 shadow-lg">
                    <div class="mb-4 flex items-center">
                        <span class="mr-2"><History class="h-7 w-7 text-purple-500" /></span>
                        <h2 class="text-xl font-semibold text-purple-700">Your Recent Bookings</h2>
                    </div>
                    <table class="text-md min-w-full rounded-md border border-gray-200 bg-white">
                        <thead class="bg-gray-100 text-left text-gray-700">
                            <tr class="text-center">
                                <th class="border-b px-4 py-2">SL</th>
                                <th class="border-b px-4 py-2">Car Image</th>
                                <th class="border-b px-4 py-2">Car Details</th>
                                <th class="border-b px-4 py-2">Start Date</th>
                                <th class="border-b px-4 py-2">End Date</th>
                                <th class="border-b px-4 py-2">Total Cost</th>
                                <th class="border-b px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(rental, index) in rentals" :key="rental.id" class="text-center hover:bg-gray-50">
                                <td class="border-b px-4 py-2">{{ index + 1 }}</td>
                                <td class="flex justify-center border-b px-4 py-2">
                                    <img :src="rental.car_image" alt="Car Image" class="h-16 w-24 rounded-md object-cover" />
                                </td>
                                <td class="border-b px-4 py-2">
                                    <div class="font-semibold">{{ rental.car.name }}</div>
                                    <div class="text-sm text-gray-500">{{ rental.car.brand }} - {{ rental.car.model }}</div>
                                </td>
                                <td class="border-b px-4 py-2"><formatDate :date="rental.start_date" /></td>
                                <td class="border-b px-4 py-2"><formatDate :date="rental.end_date" /></td>
                                <td class="border-b px-4 py-2">
                                    <FormatNumber :number="rental.total_cost" />
                                </td>
                                <td class="border-b px-4 py-2">
                                    <span
                                        class="inline-block rounded px-2 py-1 text-sm font-semibold"
                                        :class="{
                                            'bg-red-100 text-red-700': rental.status === 'Canceled',
                                            'bg-green-100 text-green-700': rental.status === 'Completed',
                                            'bg-yellow-100 text-yellow-800':
                                                ['Pending', 'Confirmed'].includes(rental.status) && new Date(rental.start_date) > new Date(),
                                            'bg-blue-100 text-blue-700': rental.status === 'Ongoing',
                                        }"
                                    >
                                        {{ rental.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="rentals.length === 0" class="mt-4 text-center text-gray-500">No recent bookings found.</div>
                </div>

                <!-- Top Rented Cars Section -->
                <div class="col-span-2 rounded-lg bg-white p-4 shadow-lg">
                    <div class="mb-4 flex items-center">
                        <span class="mr-2"><Flame class="h-8 w-8 text-red-500" /></span>
                        <h2 class="text-xl font-semibold text-red-500">Top 5 Most Popular Cars</h2>
                    </div>
                    <table class="w-full border text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr class="text-center text-gray-700">
                                <th class="px-4 py-2">Image</th>
                                <th class="px-4 py-2">Car</th>
                                <th class="px-4 py-2">Total Rentals</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(car, index) in topRentedCars" :key="car.id" class="border-t text-center hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    <img :src="car.image" alt="Car Image" class="mx-auto h-16 w-20 rounded object-cover" />
                                </td>
                                <td class="px-4 py-2">
                                    <div class="font-semibold">{{ car.name }}</div>
                                    <div class="text-sm text-gray-500">{{ car.brand }} - {{ car.model }}</div>
                                </td>
                                <td class="px-4 py-2">{{ car.rentals_count ?? 0 }}</td>
                                <td class="px-4 py-2">
                                    <button
                                        @click="goToCarDetails(car.id)"
                                        class="cursor-pointer rounded bg-blue-600 px-3 py-1 text-sm text-white hover:bg-blue-700"
                                    >
                                        Rent This
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="topRentedCars.length === 0">
                                <td colspan="4" class="py-4 text-center text-gray-500">No top rented cars found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Featured Cars Section -->
            <div class="mx-5 mb-10 p-5 bg-white rounded-lg shadow-lg">
                <FeaturedProduct :cars="page.props.featuredCars" />
            </div>
        </div>
    </AppLayout>
</template>
