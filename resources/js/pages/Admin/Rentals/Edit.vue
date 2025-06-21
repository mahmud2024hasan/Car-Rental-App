<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormatNumber from '@components/custom/FormatNumber.vue';
import { computed } from 'vue';

// Props from backend
const props = defineProps({
  rental: Object,
  customers: Array,
  cars: Array,
});


// Breadcrumbs
const breadcrumbs = [
  { title: 'Manage Rentals', href: '/admin/rentals' },
  { title: 'Edit Rental Details', href: `/admin/rentals/${props.rental.id}/edit` },
];


// Reactive form
const form = useForm({
  _method: 'put',
  customer_id: props.rental.user_id ?? '',
  car_id: props.rental.car_id ?? '',
  start_date: props.rental.start_date ?? '',
  end_date: props.rental.end_date ?? '',
  total_cost: props.rental.total_cost ?? 0,
  status: props.rental.status ?? 'Pending',
});


// Computed total cost
const totalCost = computed(() => {
  if (form.start_date && form.end_date && form.car_id) {
    const start = new Date(form.start_date);
    const end = new Date(form.end_date);

    if (start <= end) {
      const diffInMs = end - start;
      const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24)) + 1;

      const selectedCar = props.cars.find(car => car.id === form.car_id);
      if (selectedCar) {
        return selectedCar.daily_rent_price * diffInDays;
      }
    }
  }
  return 0;
});

// Formatting total cost to a string with currency
function formatNumber(amount) {
  const number = parseFloat(amount);
  if (isNaN(number)) return ''; 
  const options = {
    style: 'decimal',
    maximumFractionDigits: number % 1 === 0 ? 0 : 2,
  };
  return number.toLocaleString('en-IN', options) + ' ৳';
}

const formattedTotalCost = computed(() => {
  return formatNumber(totalCost.value);
});

// Submit form
function submitForm() {
    // Manually inject total cost
    form.total_cost = totalCost.value;

    form.post(route('admin.rentals.store'), {
        onSuccess: () => form.reset(),
    });
}

// Update form
function updateForm() {
    // Manually inject total cost
    form.total_cost = totalCost.value;

    // Update the rental
    form.post(route('admin.rentals.update', props.rental.id));
}
</script>

<template>
    <Head title="Add New Rental" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto mt-8 w-[900px] rounded-lg border border-green-300 bg-white shadow">
            <h1 class="mb-6 rounded-t-lg bg-green-600 py-2 text-center text-2xl font-semibold text-white">Edit Rental Details</h1>

            <form @submit.prevent="updateForm" class="space-y-6 p-4">
                <!-- Customer Details -->
                <div class="grid grid-cols-1">
                    <label class="text-md mb-1 block font-medium">Customer Details:</label>
                    <select v-model="form.customer_id" class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none">
                       <option value="" disabled>Select Customer</option>
                        <option 
                            v-for="customer in props.customers" 
                            :key="customer.customer_id" 
                            :value="customer.customer_id"
                            :selected="customer.customer_id === form.customer_id"
                        >
                            {{ customer.name }} [Email: {{ customer.email }}<span v-if="customer.mobile">, Phone: {{ customer.mobile }}</span>]
                        </option>
                    </select>
                    <div v-if="form.errors.customer_id" class="text-sm text-red-500">{{ form.errors.customer_id }}</div>
                </div>

                <!-- Car Details -->
                <div class="grid grid-cols-1">
                    <label class="text-md mb-1 block font-medium">Car Details:</label>
                    <select v-model="form.car_id" class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none">
                        <option value="" disabled>Select Car</option>
                        <option 
                            v-for="car in cars" 
                            :key="car.id" 
                            :value="car.id"
                            :selected="car.id === form.car_id"
                        >
                            {{ car.name }} [Brand: {{ car.brand }}, Model: {{ car.model }}, Daily Rent: <FormatNumber :number="car.daily_rent_price" />]
                        </option>
                    </select>
                </div>

                <!-- Start and End Dates -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-md mb-1 block font-medium">Start Date:</label>
                        <input
                            v-model="form.start_date"
                            type="date"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter car brand"
                        />
                        <div v-if="form.errors.start_date" class="text-sm text-red-500">{{ form.errors.start_date }}</div>
                    </div>

                    <div>
                        <label class="text-md mb-1 block font-medium">End Date:</label>
                        <input
                            v-model="form.end_date"
                            type="date"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter car model"
                        />
                        <div v-if="form.errors.end_date" class="text-sm text-red-500">{{ form.errors.end_date }}</div>
                    </div>
                </div>

                <!-- Rental Cost and Status -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-md mb-1 block font-medium">Total Cost:</label>
                        <input
                            :value="formattedTotalCost"
                            type="text"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none bg-gray-100 cursor-not-allowed"
                            placeholder="Total cost will be calculated"
                            readonly
                        />
                    </div>

                    <div>
                        <label class="text-md mb-1 block font-medium">Rental Status:</label>
                        <select v-model="form.status" class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none">
                            <option value="Pending">Pending</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                            <option value="Canceled">Canceled</option>
                        </select>
                        <div v-if="form.errors.status" class="text-sm text-red-500">{{ form.errors.status }}</div>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <Link :href="route('admin.rentals.index')" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700">Cancel</Link>
                    <button type="submit" :disabled="form.processing" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700 cursor-pointer">
                        <span v-if="form.processing">Updating...</span>
                        <span v-else>Update</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
