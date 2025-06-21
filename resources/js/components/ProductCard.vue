<script setup>
import FormatNumber from '@components/custom/FormatNumber.vue';
import { router, usePage } from '@inertiajs/vue3';

// Define the props for accessing car data
defineProps({
    car: Object,
});

// Use the page object to access user data
const page = usePage();

// If user logged in, redirect to booking page otherwise login page
function handleBookingClick(carID) {
    const user = page.props.auth.user;

    if (user) {
        if (user.role === 'customer') {
            router.visit(route('customer.bookings.create', { car_id: carID }));
        } else if (user.role === 'admin') {
            router.visit(route('admin.rentals.create', { car_id: carID }));
        } else {
            router.visit(route('login', { message: 'unauthorized' }));
        }
    } else {
        router.visit(route('login', { message: 'login_required' }));
    }
}

</script>

<template>
    <div class="rounded-xl border bg-white shadow-md transition hover:shadow-lg">
        <img :src="car.image_url" :alt="car.name" class="h-76 w-full rounded-t-xl object-cover" />
        <div class="p-4 text-center">
            <h3 class="mb-1 text-xl font-bold text-gray-800">{{ car.name }}</h3>
            <p class="mb-2 text-sm text-gray-500">{{ car.brand }} - {{ car.model }}</p>
            <div class="flex justify-between items-center mt-2">
                <p class="text-md font-semibold text-green-600">{{ car.availability ? 'Available' : 'Unavailable' }} Now</p>
                <p class="text-lg font-semibold text-orange-500"><FormatNumber :number="car.daily_rent_price" /> /day</p>
                
            </div>
            <div class="mt-2">
                <template v-if="car.is_booked">
                    <div class="w-full bg-gray-50 border border-gray-300 px-4 py-2 rounded-md font-semibold text-red-600"> Booked until {{ car.booked_until }} </div>
                </template>
                <template v-else>
                    <button @click="handleBookingClick(car.id)" class="w-full cursor-pointer rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Book Now
                    </button>
                </template>
            </div>
        </div>
    </div>
</template>