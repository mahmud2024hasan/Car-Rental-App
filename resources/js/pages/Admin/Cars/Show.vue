<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import FormatNumber from '@components/custom/FormatNumber.vue';
import { Head, Link } from '@inertiajs/vue3';

// Define the props for accessing car data
const props = defineProps({
    car: Object,
});

// Breadcrumbs for navigation
const breadcrumbs = [
    { title: 'Manage Cars', href: '/admin/cars' },
    { title: 'Car Details', href: `/admin/cars/${props.car.id}` },
];
</script>

<template>
    <Head title="Car Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Content Header Section -->
            <div class="flex items-center justify-between mx-8 my-2">
                <h1 class="text-3xl font-semibold">Car Details</h1>
                <Link :href="route('admin.cars.index')" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700">Back</Link>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Left side for car image -->
                <div class="ml-8 flex items-center justify-center">
                    <img v-if="props.car.image_url" :src="props.car.image_url" alt="Car Image" class="rounded-lg object-cover shadow-md" />
                    <div v-else class="flex h-64 w-64 items-center justify-center rounded-lg bg-gray-200 shadow-md">
                        <span class="text-gray-500">Car Image Not Available</span>
                    </div>
                </div>

                <!-- Right side for car details -->
                <div class="mr-8 ml-2">
                    <h1 class="mb-4 border-b border-gray-300 text-4xl font-semibold text-blue-600">{{ props.car.name }}</h1>
                    <p class="mb-2 text-xl text-gray-700"><span class="font-semibold">Brand:</span> {{ props.car.brand }}</p>
                    <p class="mb-2 text-xl text-gray-700"><span class="font-semibold">Model:</span> {{ props.car.model }}</p>
                    <p class="mb-2 text-xl text-gray-700"><span class="font-semibold">Car Type:</span> {{ props.car.car_type }}</p>
                    <p class="mb-2 text-xl text-gray-700"><span class="font-semibold">Mfg. Year:</span> {{ props.car.year }}</p>
                    <p class="mb-2 text-xl text-gray-700">
                        <span class="font-semibold">Daily Rent:</span> <FormatNumber :number="props.car.daily_rent_price" />
                    </p>
                    <p class="mb-2 text-xl text-gray-700">
                        <span class="font-semibold">Availability:</span> {{ props.car.availability ? 'Available' : 'Unavailable' }}
                    </p>
                    <p class="mb-2 text-xl text-gray-700"><span class="font-semibold">Total Rentals:</span> {{ props.car.rent_count }}</p>
                    <p class="mb-2 text-xl text-gray-700"><span class="font-semibold">Total Income:</span> <FormatNumber :number="props.car.total_income" /></p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
