<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Breadcrumbs for navigation
const breadcrumbs = [
    { title: 'Manage Cars', href: '/admin/cars' },
    { title: 'Add New Car', href: '/admin/cars/create' },
];

// Define the form structure
const form = useForm({
    name: '',
    brand: '',
    model: '',
    color: '',
    year: '',
    car_type: '',
    daily_rent_price: '',
    availability: '',
    image: null,
});

// For image preview
const previewImage = ref(null);

// Handle image change
function handleImageChange(event) {
    const file = event.target.files[0];
    form.image = file;
    previewImage.value = file ? URL.createObjectURL(file) : null;
}

// Submit the form
function submitForm() {
    form.post(route('admin.cars.store'), {
        onSuccess: () => {
            // Reset the form and preview image after successful submission
            form.reset();
            previewImage.value = null;
        },
    });
}
</script>

<template>
    <Head title="Add New Car" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto mt-8 w-[900px] rounded-lg border border-green-300 bg-white shadow">
            <h1 class="mb-6 rounded-t-lg bg-green-600 py-2 text-center text-2xl font-semibold text-white">Add New Car</h1>

            <form @submit.prevent="submitForm" class="space-y-6 p-4">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-md mb-1 block font-medium">Car Name:</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter car name"
                        />
                        <div v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-md mb-1 block font-medium">Brand:</label>
                        <input
                            v-model="form.brand"
                            type="text"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter car brand"
                        />
                        <div v-if="form.errors.brand" class="text-sm text-red-500">{{ form.errors.brand }}</div>
                    </div>

                    <div>
                        <label class="text-md mb-1 block font-medium">Model:</label>
                        <input
                            v-model="form.model"
                            type="text"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter car model"
                        />
                        <div v-if="form.errors.model" class="text-sm text-red-500">{{ form.errors.model }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-md mb-1 block font-medium">Car Type:</label>
                        <select v-model="form.car_type" class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none">
                            <option value="" disabled>Select Car Type</option>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Hatchback">Hatchback</option>
                            <option value="Coupe">Coupe</option>
                            <option value="Convertible">Convertible</option>
                            <option value="Wagon">Wagon</option>
                            <option value="Crossover">Crossover</option>
                            <option value="Limousine">Limousine</option>
                            <option value="Sports Car">Sports Car</option>
                            <option value="Electric">Electric</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                        <div v-if="form.errors.car_type" class="text-sm text-red-500">{{ form.errors.car_type }}</div>
                    </div>

                    <div>
                        <label class="text-md mb-1 block font-medium">Year of Manufacture:</label>
                        <input
                            v-model="form.year"
                            type="number"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter year of manufacture"
                        />
                        <div v-if="form.errors.year" class="text-sm text-red-500">{{ form.errors.year }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-md mb-1 block font-medium">Daily Rent Price:</label>
                        <input
                            v-model="form.daily_rent_price"
                            type="number"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter daily rent price"
                        />
                        <div v-if="form.errors.daily_rent_price" class="text-sm text-red-500">{{ form.errors.daily_rent_price }}</div>
                    </div>

                    <div>
                        <label class="text-md mb-1 block font-medium">Availability Status:</label>
                        <select
                            v-model="form.availability"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                        >
                            <option value="" disabled selected>Select Availability Status</option>
                            <option :value="true">Available</option>
                            <option :value="false">Unavailable</option>
                        </select>
                        <div v-if="form.errors.availability" class="text-sm text-red-500">{{ form.errors.availability }}</div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- File Input -->
                    <div>
                        <label class="text-md mb-1 block font-medium">Car Image:</label>
                        <input
                            type="file"
                            accept="image/*"
                            class="w-full cursor-pointer rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            @change="handleImageChange"
                        />
                        <div v-if="form.errors.image" class="text-sm text-red-500">{{ form.errors.image }}</div>
                    </div>

                    <!-- Preview Section -->
                    <div v-if="previewImage" class="mt-2">
                        <img :src="previewImage" alt="Car Image Preview" class="mb-2 w-full rounded border border-gray-300" />

                        <!-- Show File Name and size converting to mb if size > 1mb and if < 1mb convert to kb -->
                        <p class="text-sm text-gray-600"><strong>Image Name:</strong> {{ form.image.name }}</p>
                        <p class="text-sm text-gray-600">
                            <strong>Image Size:</strong>
                            {{
                                form.image.size > 1024 * 1024
                                    ? (form.image.size / 1024 / 1024).toFixed(2) + ' MB'
                                    : (form.image.size / 1024).toFixed(2) + ' KB'
                            }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <Link :href="route('admin.cars.index')" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700">Cancel</Link>
                    <button type="submit" :disabled="form.processing" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                        <span v-if="form.processing">Submitting...</span>
                        <span v-else>Add Car</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
