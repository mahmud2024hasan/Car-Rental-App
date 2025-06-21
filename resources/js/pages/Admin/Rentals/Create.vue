<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import FormatNumber from '@components/custom/FormatNumber.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

const props = defineProps({
    customers: Array,
    cars: Array,
    initialCarId: [Number, String],
});

const form = useForm({
    customer_id: '',
    car_id: props.initialCarId || '',
    start_date: '',
    end_date: '',
    status: 'Pending',
});

onMounted(() => {
    // If a car_id was passed via query, prefill it
    if (props.initialCarId) {
        form.car_id = props.initialCarId;
    }
});

const selectedCar = computed(() => props.cars.find(car => car.id === Number(form.car_id)));
const selectedCustomer = computed(() => props.customers.find(c => c.customer_id === Number(form.customer_id)));

const rentalDuration = computed(() => {
    if (form.start_date && form.end_date) {
        const start = new Date(form.start_date);
        const end = new Date(form.end_date);
        const diff = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
        return diff > 0 ? diff : 0;
    }
    return 0;
});

const totalCost = computed(() => {
    return selectedCar.value ? selectedCar.value.daily_rent_price * rentalDuration.value : 0;
});

const formatNumber = (amount) => {
    const number = parseFloat(amount);
    if (isNaN(number)) return '';
    return (
        number.toLocaleString('en-IN', {
            style: 'decimal',
            maximumFractionDigits: number % 1 === 0 ? 0 : 2,
        }) + ' ৳'
    );
};

function submitForm() {
    form.total_cost = totalCost.value;
    form.post(route('admin.rentals.store'), {
        onSuccess: () => form.reset(),
    });
}

const breadcrumbs = [
    { title: 'Manage Rentals', href: '/admin/rentals' },
    { title: 'Add New Rental', href: '/admin/rentals/create' },
];
</script>


<template>
    <Head title="Add New Rental" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <form>
            <div class="flex w-full flex-col items-center">
                <!-- Content Header -->
                <div class="flex w-full items-center justify-between gap-10 bg-gradient-to-b from-blue-500 via-blue-200 to-blue-100 p-5">
                    <div class="">
                        <h1 class="py-2 text-center text-2xl font-bold text-gray-900">Add New Rental Manually</h1>
                    </div>

                    <!-- Back Button -->
                    <div class="">
                        <Link :href="route('admin.rentals.index')" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700">Back</Link>
                    </div>
                </div>

                <!-- Select and pick section -->
                <div class="flex w-full items-start justify-start gap-8 border-b border-gray-200 px-10 py-8">
                    <!-- Select Car -->
                    <div class="w-[30%]">
                        <label class="text-md mb-1 block font-medium text-gray-500">Select Car:</label>
                        <select v-model="form.car_id" class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none">
                            <option value="" disabled>Select Car</option>
                            <option v-for="car in cars" :key="car.id" :value="car.id">
                                {{ car.name }} [Brand: {{ car.brand }}, Model: {{ car.model }}, Daily Rent:
                                <FormatNumber :number="car.daily_rent_price" />]
                            </option>
                        </select>
                    </div>

                    <!-- Select Customer -->
                    <div v-if="selectedCar" class="w-[30%]">
                        <label class="text-md mb-1 block font-medium text-gray-500">Select Customer:</label>
                        <select
                            v-model="form.customer_id"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                        >
                            <option value="" disabled>Select Customer</option>
                            <option v-for="customer in customers" :key="customer.customer_id" :value="customer.customer_id">
                                {{ customer.name }} [Email: {{ customer.email }} <span v-if="customer.mobile">, Phone: {{ customer.mobile }}</span
                                >]
                            </option>
                        </select>
                        <div v-if="form.errors.customer_id" class="text-sm text-red-500">{{ form.errors.customer_id }}</div>
                    </div>

                    <!-- Select Start and End Dates -->
                    <div v-if="selectedCustomer" class="grid w-[40%] grid-cols-3 gap-4">
                        <div>
                            <label class="text-md mb-1 block font-medium text-gray-500">Start Date:</label>
                            <input
                                v-model="form.start_date"
                                type="date"
                                class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                                placeholder="Enter car brand"
                            />
                            <div v-if="form.errors.start_date" class="text-sm text-red-500">{{ form.errors.start_date }}</div>
                        </div>

                        <div>
                            <label class="text-md mb-1 block font-medium text-gray-500">End Date:</label>
                            <input
                                v-model="form.end_date"
                                type="date"
                                class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                                placeholder="Enter car model"
                            />
                            <div v-if="form.errors.end_date" class="text-sm text-red-500">{{ form.errors.end_date }}</div>
                        </div>

                        <div>
                            <label class="text-md mb-1 block font-medium text-gray-500">Rental Status:</label>
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
                </div>

                <!-- Main Content -->
                <div class="w-full">
                    <div v-if="selectedCar" class="bg-gray-100 px-6 py-10">
                        <div class="mx-auto grid w-full grid-cols-1 gap-5 rounded-xl bg-white shadow-lg md:grid-cols-2">
                            <!-- Car Details -->
                            <div class="flex flex-col gap-4 px-10 py-8">
                                <div class="border-b border-gray-200">
                                    <h1 class="block text-3xl font-semibold text-blue-600">{{ selectedCar.name }}</h1>
                                    <span class="text-lg text-gray-700">
                                        Brand: {{ selectedCar.brand }}, Model: {{ selectedCar.model }}, Type: {{ selectedCar.car_type }}, Mfg. Year: {{ selectedCar.year }} 
                                    </span>
                                </div>
                                <img
                                    v-if="selectedCar?.image_url"
                                    :src="selectedCar.image_url"
                                    alt="Car Image"
                                    class="mt-2 h-[450px] w-auto rounded object-cover"
                                />
                            </div>

                            <!-- Customer and Rental details -->
                            <div class="m-5 flex flex-col justify-between">
                                <!-- Customer Details -->
                                <div v-if="selectedCustomer" class="my-5">
                                    <h2 class="mb-1 border-b border-gray-300 text-2xl font-semibold text-green-700">Customer Details:</h2>
                                    <div class="flex justify-between gap-5">
                                        <div class="aspect-[4/5] w-68">
                                            <img
                                                :src="selectedCustomer?.image_url ?? '/male-profile-avatar.png'"
                                                alt="Profile Picture"
                                                class="h-full w-full rounded-lg border-4 border-white object-cover shadow-xl"
                                            />
                                        </div>

                                        <div class="mt-1 flex w-full flex-col gap-1">
                                            <p class="text-lg"><span class="text-gray-700">Name:</span> {{ selectedCustomer?.name ?? '' }}</p>
                                            <p class="text-lg"><span class="text-gray-700">Email:</span> {{ selectedCustomer?.email ?? '' }}</p>
                                            <p class="text-lg"><span class="text-gray-700">Mobile:</span> {{ selectedCustomer?.mobile ?? '' }}</p>
                                            <p class="text-lg"><span class="text-gray-700">Address:</span> {{ selectedCustomer?.address ?? '' }}</p>
                                            <p class="text-lg"><span class="text-gray-700">District:</span> {{ selectedCustomer?.district ?? '' }}</p>
                                            <p class="text-lg"><span class="text-gray-700">Division:</span> {{ selectedCustomer?.division ?? '' }}</p>
                                            <p class="text-lg">
                                                <span class="text-gray-700">Postal Code:</span> {{ selectedCustomer?.postal_code ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rental Details -->
                                <div v-if="selectedCar && form.start_date && form.end_date" class="mt-5">
                                    <h3 class="mb-2 text-xl font-semibold text-purple-700">Rental Details:</h3>

                                    <div class="mb-4 grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="mb-2 flex items-center">
                                                <label class="w-[28%] rounded-l border border-gray-300 bg-gray-100 px-2 py-2 text-gray-700"
                                                    >Start Date:</label
                                                >
                                                <div class="w-[72%] rounded-r border border-gray-300 px-3 py-2">{{ form.start_date || 'N/A' }}</div>
                                            </div>
                                            <div class="flex items-center">
                                                <label class="w-[28%] rounded-l border border-gray-300 bg-gray-100 px-2 py-2 text-gray-700"
                                                    >End Date:</label
                                                >
                                                <div class="w-[72%] rounded-r border border-gray-300 px-3 py-2">{{ form.end_date || 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="rounded-md border border-gray-300 bg-gray-50 px-4 py-1">
                                            <div class="mb-1">
                                                <p class="text-md w-full"><span class="text-gray-700">Duration:</span> {{ rentalDuration }} days</p>
                                            </div>
                                            <div class="mb-1">
                                                <p class="text-md w-full">
                                                    <span class="text-gray-700">Daily Rent:</span>
                                                    <span class="font-semibold text-orange-500 ml-1">
                                                        <FormatNumber :number="selectedCar?.daily_rent_price || 0" />
                                                    </span>
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-md w-full">
                                                    <span class="text-gray-700">Total Cost:</span>
                                                    <span class="font-semibold text-orange-500 ml-1">{{ formatNumber(totalCost) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        @click.prevent="submitForm"
                                        type="submit"
                                        class="w-full cursor-pointer rounded-lg bg-blue-600 px-4 py-2 text-lg font-semibold text-white transition-all duration-200 hover:bg-blue-700"
                                    >
                                        Confirm Rental
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
