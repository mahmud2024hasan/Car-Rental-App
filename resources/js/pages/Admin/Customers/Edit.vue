<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Define props
const props = defineProps({
    customer: Object,
});

// Accept the customer data with computed properties
const customer = computed(() => props.customer || {});

// Breadcrumbs for navigation
const breadcrumbs = [
    { title: 'Manage Customers', href: '/admin/customers' },
    { title: 'Edit Customer', href: '/admin/customers/edit' },
];

// Define form structure
const form = useForm({
    _method: 'PUT',
    name: customer.value.name || '',
    email: customer.value.email || '',
    mobile: customer.value.customer_profile?.mobile || '',
    address: customer.value.customer_profile?.address || '',
    district: customer.value.customer_profile?.district || '',
    division: customer.value.customer_profile?.division || '',
    postal_code: customer.value.customer_profile?.postal_code || '',
    image: null,
});

// For image preview
const previewImage = ref(props.customer.image_url ?? null);

// Handle image change
function handleImageChange(event) {
    const file = event.target.files[0];
    form.image = file;
    previewImage.value = file ? URL.createObjectURL(file) : null;
}

// Update the form
function updateForm() {
    form.post(route('admin.customers.update', customer.value.id), {
        onSuccess: () => {
            form.reset(); // Reset the form after successful submission
        },
    });
}
</script>

<template>
    <Head title="Edit Customer" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto mt-8 w-[900px] rounded-lg border border-green-300 bg-white shadow">
            <h1 class="mb-6 rounded-t-lg bg-green-600 py-2 text-center text-2xl font-semibold text-white">Edit Customer</h1>

            <form @submit.prevent="updateForm" class="space-y-6 p-4">
                <!-- Customer Name -->
                <div class="grid grid-cols-1">
                    <label class="text-md mb-1 block font-medium">Customer Name:</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                        placeholder="Enter customer name"
                    />
                    <div v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</div>
                </div>

                <!-- Customer Email and Phone -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-md mb-1 block font-medium">Customer Email:</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter customer email"
                        />
                        <div v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</div>
                    </div>
                    <div>
                        <label class="text-md mb-1 block font-medium">Customer Phone:</label>
                        <input
                            v-model="form.mobile"
                            type="text"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter customer phone"
                        />
                        <div v-if="form.errors.mobile" class="text-sm text-red-500">{{ form.errors.mobile }}</div>
                    </div>
                </div>

                <!-- Customer Address -->
                <div class="grid grid-cols-1">
                    <label class="text-md mb-1 block font-medium">Customer Address:</label>
                    <input
                        v-model="form.address"
                        type="text"
                        class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                        placeholder="Enter customer address"
                    />
                    <div v-if="form.errors.address" class="text-sm text-red-500">{{ form.errors.address }}</div>
                </div>

                <!-- Customer District, Division, and Postal Code -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-md mb-1 block font-medium">District/City:</label>
                        <input
                            v-model="form.district"
                            type="text"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter customer district"
                        />
                        <div v-if="form.errors.district" class="text-sm text-red-500">{{ form.errors.district }}</div>
                    </div>
                    <div>
                        <label class="text-md mb-1 block font-medium">Division/State:</label>
                        <input
                            v-model="form.division"
                            type="text"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter customer division"
                        />
                        <div v-if="form.errors.division" class="text-sm text-red-500">{{ form.errors.division }}</div>
                    </div>
                    <div>
                        <label class="text-md mb-1 block font-medium">Postal Code:</label>
                        <input
                            v-model="form.postal_code"
                            type="text"
                            class="w-full rounded border border-gray-300 p-2 focus:border-green-500 focus:outline-none"
                            placeholder="Enter postal_code"
                        />
                        <div v-if="form.errors.postal_code" class="text-sm text-red-500">{{ form.errors.postal_code }}</div>
                    </div>
                </div>

                <!-- Image Update -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- File Input -->
                    <div>
                        <label class="text-md mb-1 block font-medium">Profile Picture:</label>
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
                        <div class="flex justify-center">
                            <img :src="previewImage" alt="Profile Image" class="mb-2 h-64 w-64 rounded-full border-5 border-white shadow-xl" />
                        </div>
                        <p class="text-sm text-gray-600"><strong>Image Name:</strong> {{ form.image?.name ?? 'Existing Image' }}</p>
                        <p v-if="form.image" class="text-sm text-gray-600">
                            <strong>Image Size:</strong>
                            {{
                                form.image.size > 1024 * 1024
                                    ? (form.image.size / 1024 / 1024).toFixed(2) + ' MB'
                                    : (form.image.size / 1024).toFixed(2) + ' KB'
                            }}
                        </p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-between">
                    <Link :href="route('admin.customers.index')" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700">Cancel</Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="cursor-pointer rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                    >
                        <span v-if="form.processing">Updating...</span>
                        <span v-else>Update</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
