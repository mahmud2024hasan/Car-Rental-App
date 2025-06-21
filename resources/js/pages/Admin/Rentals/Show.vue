<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import FormatNumber from '@components/custom/FormatNumber.vue';
import { Head, Link } from '@inertiajs/vue3';

// Define the props for accessing rental data
const { rental } = defineProps({
    rental: Object,
});

// Breadcrumbs for navigation
const breadcrumbs = [
    { title: 'Manage Rentals', href: '/admin/rentals' },
    { title: 'Rental Details', href: `/admin/rentals/${rental.id}` },
];

// Calculate total days
const totalDays = new Date(rental.end_date) - new Date(rental.start_date);
const totalDaysCount = Math.ceil(totalDays / (1000 * 60 * 60 * 24)) + 1; // Adding 1 to include the start day
rental.total_days = totalDaysCount;

</script>

<template>
    <Head title="Rental Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
            <!-- Content Header Section -->
            <div class="flex items-center justify-between border-b border-gray-300 pb-4 bg-gradient-to-b from-blue-500 via-blue-200 to-blue-100 px-5 py-10">
                <h1 class="text-3xl font-semibold">Rental Details</h1>
                <Link :href="route('admin.rentals.index')" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700">Back</Link>
            </div>

            <div class="grid grid-cols-5 gap-4">
                <!-- Left side for details -->
                <div class="col-span-3 ml-8 flex flex-col items-start justify-start">
                    <!-- Rental Information -->
                    <div class="mb-6 w-full rounded-lg bg-white p-4 shadow-lg">
                        <h2 class="mb-1 border-b border-gray-300 text-xl font-semibold text-[#ff00ff]">Rental Information:</h2>
                        <p class="text-lg"><span class="font-semibold">Rental ID:</span> {{ rental.id }}</p>
                        <p class="text-lg"><span class="font-semibold">Status:</span> {{ rental.status }}</p>
                        <p class="text-lg"><span class="font-semibold">Start Date:</span> {{ rental.start_date }}</p>
                        <p class="text-lg"><span class="font-semibold">End Date:</span> {{ rental.end_date }}</p>
                        <p class="text-lg"><span class="font-semibold">Duration:</span> {{ rental.total_days }} Days</p>
                        <p class="text-lg"><span class="font-semibold">Daily Rent:</span> <FormatNumber :number="rental.car_details.daily_rent_price" /></p>
                        <p class="text-lg"><span class="font-semibold">Total Rent:</span> <FormatNumber :number="rental.total_cost" /></p>
                        
                    </div>

                    <!-- Customer Details -->
                    <div class="mb-4 w-full rounded-lg bg-green-100 p-4 shadow-lg">
                        <h2 class="mb-1 border-b border-gray-300 text-xl font-semibold text-green-700">Customer Details:</h2>
                        <p class="text-lg"><span class="font-semibold">Name:</span> {{ rental.customer_details.name }}</p>
                        <p class="text-lg"><span class="font-semibold">Email:</span> {{ rental.customer_details.email }}</p>
                        <p class="text-lg"><span class="font-semibold">Mobile:</span> {{ rental.customer_details.mobile }}</p>
                        <p class="text-lg"><span class="font-semibold">Address:</span> {{ rental.customer_details.address }}</p>
                        <p class="text-lg"><span class="font-semibold">City:</span> {{ rental.customer_details.city }}</p>
                        <p class="text-lg"><span class="font-semibold">Country:</span> {{ rental.customer_details.country }}</p>
                    </div>
                </div>

                <!-- Right side for car Details -->
                <div class="col-span-2 mr-8 ml-2 rounded-lg bg-blue-50 p-4 shadow-lg">
                    <!-- Car Details -->
                    <div class="p-4">
                        <h2 class="mb-1 border-b border-gray-300 text-xl font-semibold text-blue-700">Car Details:</h2>
                        <div class="grid grid-cols-2">
                            <p class="text-lg"><span class="font-semibold">Name:</span> {{ rental.car_details.name }}</p>
                            <p class="text-lg"><span class="font-semibold">Brand:</span> {{ rental.car_details.brand }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="text-lg"><span class="font-semibold">Model:</span> {{ rental.car_details.model }}</p>
                            <p class="text-lg"><span class="font-semibold">Car Type:</span> {{ rental.car_details.car_type }}</p>
                        </div>
                    </div>
                    <!-- Car Image -->
                    <div class="w-full">
                        <img :src="rental.car_details.image_url" alt="Car Image" class="rounded h-[400px]" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
