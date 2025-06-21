<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import FormatNumber from '@components/custom/FormatNumber.vue'; 
import { Head, router } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

// Define the props for accessing the specific car data
const props = defineProps({
    car: Object, 
});

const form = reactive({
    start_date: '',
    end_date: '',
});


function formatNumber(amount) {
  const number = parseFloat(amount);
  if (isNaN(number) || number === 0) return '0 ৳';

  const options = {
    style: 'decimal',
    maximumFractionDigits: 2,
  };
  return number.toLocaleString('en-IN', options) + ' ৳';
}

/**
 * A primary computed property to calculate both total days and total cost.
 * This is now corrected to use the `props.car` object directly.
 */
const bookingDetails = computed(() => {
  // Check if dates are selected and the car object from props exists.
  if (form.start_date && form.end_date && props.car) {
    const start = new Date(form.start_date);
    const end = new Date(form.end_date);

    // Ensure the end date is on or after the start date.
    if (start <= end) {
      const diffInMs = end.getTime() - start.getTime();
      const totalDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24)) + 1;

      // Directly use the daily rent price from the props.car object.
      // No need to find the car in an array.
      const totalCost = props.car.daily_rent_price * totalDays;
      
      return {
        totalDays: totalDays,
        totalCost: totalCost,
      };
    }
  }

  // If any condition fails, return default zero values.
  return {
    totalDays: 0,
    totalCost: 0,
  };
});


/**
 * Computed property to display only the total number of days.
 * This property remains unchanged.
 */
const totalDaysDisplay = computed(() => {
    if (bookingDetails.value.totalDays > 0) {
        // Use singular 'day' or plural 'days' based on the count
        const dayText = bookingDetails.value.totalDays > 1 ? 'days' : 'day';
        return `${bookingDetails.value.totalDays} ${dayText}`;
    }
    return ''; 
});


/**
 * Computed property to display the formatted total cost.
 * This property remains unchanged.
 */
const formattedTotalCost = computed(() => {
    return formatNumber(bookingDetails.value.totalCost);
});


// Submit Booking Form
function submitBooking() {
    router.post('/customer/bookings', {
        car_id: props.car.id, 
        start_date: form.start_date,
        end_date: form.end_date,
    });
}

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Customer Dashboard',
        href: '/customer/dashboard',
    },
    {
        title: 'Booking Car',
        href: '/customer/bookings/create',
    },
];
</script>

<template>
    <!-- Head Tag -->
    <Head title="Booking Car" />

    <AppLayout :breadcrumbs="breadcrumbs" >
        <div class="bg-gray-100 px-6 pt-10 pb-50">
            <div class="mx-auto grid w-full grid-cols-1 gap-10 rounded-xl bg-white p-8 shadow-lg md:grid-cols-2">
                <!-- Car Image -->
                <div class="flex items-center justify-center">
                    <img :src="car.image_url" alt="Car Image" class="h-auto max-h-[500px] w-full rounded-xl object-cover shadow-md" />
                </div>

                <!-- Car Details and Booking Form -->
                <div class="flex flex-col justify-between">
                    <!-- Car Details -->
                    <div>
                        <h2 class="mb-2 border-b border-gray-300 text-3xl font-bold text-blue-700">{{ car.name }}</h2>
                        <p class="mb-1 text-lg text-gray-600">
                            Brand: <span class="font-medium">{{ car.brand }}</span>
                        </p>
                        <p class="mb-1 text-lg text-gray-600">
                            Model: <span class="font-medium">{{ car.model }}</span>
                        </p>
                        <p class="mb-1 text-lg text-gray-600">
                            Car Type: <span class="font-medium">{{ car.car_type }}</span>
                        </p>
                        <p class="mb-1 text-lg text-gray-600">
                            Mfg. Year: <span class="font-medium">{{ car.year }}</span>
                        </p>
                        <p class="mb-1 text-lg text-gray-600">
                            Status: <span class="font-medium bg-green-500 px-3 py-1 text-white rounded-sm">{{ car.availability ? 'Available' : 'Unavailable' }}</span>
                        </p>
                        <p class="mb-1 text-lg text-gray-600">
                            Rent Price: <span class="font-semibold text-orange-500"> <FormatNumber :number="car.daily_rent_price" /> /day</span>
                        </p>
                    </div>

                    <!-- Booking Form -->
                    <div class="mt-8">
                        <h3 class="mb-2 text-xl font-semibold text-gray-700">Rent This Car:</h3>
                        <form @submit.prevent="submitBooking">
                            <div class="mb-4 grid grid-cols-2 gap-4">
                                <div>
                                    <div class="mb-2 flex items-center">
                                        <label for="start_date" class="w-[28%] rounded-l border border-gray-300 bg-gray-100 px-2 py-2 text-gray-700"
                                            >Start Date:</label
                                        >
                                        <input
                                            type="date"
                                            id="start_date"
                                            v-model="form.start_date"
                                            class="w-[72%] rounded-r border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                            required
                                        />
                                    </div>
                                    <div class="flex items-center">
                                        <label for="start_date" class="w-[28%] rounded-l border border-gray-300 bg-gray-100 px-2 py-2 text-gray-700"
                                            >End Date:</label
                                        >
                                        <input
                                            type="date"
                                            id="end_date"
                                            v-model="form.end_date"
                                            class="w-[72%] rounded-r border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="rounded-md border border-gray-300 bg-gray-50 py-2 px-4">
                                    <div class="my-2">
                                        <p class="w-full text-lg">
                                            <span class="w-full text-gray-700">Duration: </span> {{ totalDaysDisplay }}
                                        </p>
                                    </div>
                                    <div class="">
                                        <p class="w-full text-lg">
                                            <span class="mb-1 w-full text-gray-700">Total Cost: </span>
                                            <span class="font-semibold text-orange-500">{{ formattedTotalCost }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <button
                                type="submit"
                                class="w-full rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white transition-all duration-200 hover:bg-blue-700 cursor-pointer text-lg"
                            >
                                Confirm Your Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
