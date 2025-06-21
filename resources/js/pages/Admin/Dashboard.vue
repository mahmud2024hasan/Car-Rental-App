<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import FormatNumber from '@components/custom/FormatNumber.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Car, CarFront, CarTaxiFront, HandCoins, Handshake, UserCog, UserPlus } from 'lucide-vue-next';

// Breadcrumbs for navigation
const breadcrumbs = [{ title: 'Admin Dashboard', href: '/admin/dashboard' }];

// Define props for the component
const props = defineProps({
    totalCars: Number,
    totalAvailableCars: Number,
    totalRentals: Number,
    totalEarnings: Number,
    recentRentals: Array,
    latestCars: Array,
    topRentedCars: Array,
    topCustomer: Object,
});

// Calculate total days for each rental
props.recentRentals.forEach((rental) => {
    const totalDays = new Date(rental.end_date) - new Date(rental.start_date);
    const totalDaysCount = Math.ceil(totalDays / (1000 * 60 * 60 * 24)) + 1; // Adding 1 to include the start day
    rental.total_days = totalDaysCount;
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 bg-gray-100">
            <!-- Statistics for cars, rentals and earnings from rentals -->
            <div class="mt-4 grid grid-cols-4 gap-4 px-4">
                <!-- Total number of cars -->
                <div class="flex h-36 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-2/5 items-center justify-center bg-orange-500 p-4">
                        <Car class="h-24 w-24 text-white" />
                    </div>
                    <div class="w-3/5 px-4 text-gray-700">
                        <h3 class="mb-2 text-xl">Total Cars</h3>
                        <p class="ml-1 text-4xl font-semibold text-orange-500">{{ props.totalCars }}</p>
                    </div>
                </div>

                <!-- Total number of available cars -->
                <div class="flex h-36 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-1/3 items-center justify-center bg-green-600 p-4">
                        <CarTaxiFront class="h-24 w-24 text-white" />
                    </div>
                    <div class="px-4 text-gray-700">
                        <h3 class="mb-2 text-xl">Available Cars</h3>
                        <p class="ml-1 text-4xl font-semibold text-green-700">{{ props.totalAvailableCars }}</p>
                    </div>
                </div>

                <!-- Total number of rentals -->
                <div class="flex h-36 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-1/3 items-center justify-center bg-purple-600 p-4">
                        <Handshake class="h-24 w-24 text-white" />
                    </div>
                    <div class="px-4 text-gray-700">
                        <h3 class="mb-2 text-xl">Total Rentals</h3>
                        <p class="ml-1 text-4xl font-semibold text-purple-700">{{ props.totalRentals }}</p>
                    </div>
                </div>

                <!-- Total number of earnings from rentals -->
                <div class="flex h-36 items-center overflow-hidden rounded-sm border bg-white shadow">
                    <div class="flex h-full w-1/3 items-center justify-center bg-red-500 p-4">
                        <HandCoins class="h-24 w-24 text-white" />
                    </div>
                    <div class="px-4 text-gray-700">
                        <h3 class="mb-2 text-xl">Total Earnings</h3>
                        <p class="ml-1 text-4xl font-semibold text-red-500"><FormatNumber :number="props.totalEarnings" /></p>
                    </div>
                </div>
            </div>

            <!-- Useful Buttons Links -->
            <div class="mt-4 grid grid-cols-4 gap-4 px-4">
                <Link
                    :href="route('admin.cars.create')"
                    class="flex cursor-pointer items-center justify-center rounded-lg bg-orange-500 p-4 text-white shadow hover:bg-orange-600"
                >
                    <CarFront class="mr-2 h-6 w-6" />
                    Add New Car
                </Link>
                <Link
                    :href="route('admin.rentals.create')"
                    class="flex cursor-pointer items-center justify-center rounded-lg bg-green-600 p-4 text-white shadow hover:bg-green-700"
                >
                    <Handshake class="mr-2 h-6 w-6" />
                    Add New Rental
                </Link>
                <Link
                    :href="route('admin.customers.create')"
                    class="flex cursor-pointer items-center justify-center rounded-lg bg-purple-600 p-4 text-white shadow hover:bg-purple-700"
                >
                    <UserPlus class="mr-2 h-6 w-6" />
                    Add New Customer
                </Link>
                <Link href="#" class="flex cursor-pointer items-center justify-center rounded-lg bg-red-500 p-4 text-white shadow hover:bg-red-700">
                    <UserCog class="mr-2 h-6 w-6" />
                    Add New Admin
                </Link>
            </div>

            <!-- Recent Activities -->
            <div class="grid grid-cols-3 gap-4 p-4">
                <!-- Recent Rentals Section -->
                <div class="col-span-2 rounded-lg bg-white p-4 shadow-lg">
                    <h2 class="mb-4 text-2xl font-semibold text-purple-700">Recent Rentals Booking</h2>

                    <table class="w-full border text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr class="text-center text-gray-700">
                                <th class="px-4 py-2">SL</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Car</th>
                                <th class="px-4 py-2">Total Price</th>
                                <th class="px-4 py-2">Duration</th>
                                <th class="px-4 py-2">Booking Time</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(rental, index) in recentRentals" :key="rental.id" class="border-t text-center hover:bg-gray-50">
                                <td class="px-4 py-2">{{ index + 1 }}</td>
                                <td class="px-4 py-2">{{ rental.user?.name ?? '' }}</td>
                                <td class="px-4 py-2">{{ rental.car?.model ?? '' }}</td>
                                <td class="px-4 py-2"><FormatNumber :number="rental.total_cost" /></td>
                                <td class="px-4 py-2">{{ rental.total_days ?? 0 }} days</td>
                                <td class="px-4 py-2">{{ new Date(rental.created_at).toLocaleString() ?? '' }}</td>
                                <td class="px-4 py-2">{{ rental.status ?? '' }}</td>
                            </tr>
                            <tr v-if="recentRentals.length === 0">
                                <td colspan="7" class="py-4 text-center text-gray-500">No recent rentals found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Top Rented Cars Section -->
                <div class="rounded-lg bg-white p-4 shadow-lg">
                    <h2 class="mb-4 text-2xl font-semibold text-green-700">Top Rented Cars</h2>

                    <table class="w-full border text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr class="text-center text-gray-700">
                                <th class="px-4 py-2">Image</th>
                                <th class="px-4 py-2">Car</th>
                                <th class="px-4 py-2">Total Rentals</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(topRentedCar, index) in topRentedCars" :key="topRentedCar.id" class="border-t text-center hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    <img :src="`/storage/${topRentedCar.image}`" alt="Car Image" class="mx-auto h-16 w-20 rounded object-cover" />
                                </td>
                                <td class="px-4 py-2">
                                    <span class="font-semibold">{{ topRentedCar.name ?? '' }}</span
                                    ><br />
                                    <span class="text-gray-500">({{ topRentedCar.brand ?? '' }}-{{ topRentedCar.model ?? '' }})</span>
                                </td>
                                <td class="px-4 py-2">{{ topRentedCar.rental_count ?? 0 }}</td>
                            </tr>
                            <tr v-if="topRentedCars.length === 0">
                                <td colspan="7" class="py-4 text-center text-gray-500">No top rented cars found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="gap-4 p-4">
                <!-- Recent Added Cars Section -->
                <div class="rounded-lg bg-white p-4 shadow-lg">
                    <h2 class="mb-4 text-2xl font-semibold text-gray-900">Recent Added Cars</h2>

                    <table class="w-full border text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr class="text-center text-gray-700">
                                <th class="px-4 py-2">SL</th>
                                <th class="px-4 py-2">Car Image</th>
                                <th class="px-4 py-2">Car Name</th>
                                <th class="px-4 py-2">Model</th>
                                <th class="px-4 py-2">Brand</th>
                                <th class="px-4 py-2">Type</th>
                                <th class="px-4 py-2">Mfg. Year</th>
                                <th class="px-4 py-2">Added Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(car, index) in latestCars" :key="car.id" class="border-t text-center hover:bg-gray-50">
                                <td class="px-4 py-2">{{ index + 1 }}</td>
                                <td class="px-4 py-2">
                                    <img :src="car.image_url" alt="Car Image" class="mx-auto h-16 w-20 rounded object-cover" />
                                </td>
                                <td class="px-4 py-2">{{ car.name ?? '' }}</td>
                                <td class="px-4 py-2">{{ car.model ?? '' }}</td>
                                <td class="px-4 py-2">{{ car.brand ?? '' }}</td>
                                <td class="px-4 py-2">{{ car.car_type ?? '' }}</td>
                                <td class="px-4 py-2">{{ car.year ?? '' }}</td>
                                <td class="px-4 py-2">{{ new Date(car.created_at).toLocaleString() ?? '' }}</td>
                            </tr>
                            <tr v-if="latestCars.length === 0">
                                <td colspan="3" class="py-4 text-center text-gray-500">No recent added cars found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
