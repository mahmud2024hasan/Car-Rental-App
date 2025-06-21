<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

// Define the props for accessing customer data
const props = defineProps({
    customer: Object,
});

// Accept the customer data with computed properties
const customer = computed(() => props.customer || {});

// Breadcrumbs for navigation
const breadcrumbs = [
    { title: 'Manage Customers', href: '/admin/customers' },
    { title: 'Customer Details', href: `/admin/customers/${customer.id}` },
];
</script>

<template>
    <Head title="Customer Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto mt-8 w-[1000px] rounded-lg bg-white shadow">
            <!-- Customer Profile Header -->
            <div
                class="flex items-center justify-between border-b border-gray-200 bg-gradient-to-b from-blue-500 via-blue-200 to-blue-100 px-4 py-7 sm:px-6"
            >
                <div>
                    <h3 class="text-3xl leading-6 font-medium text-gray-900">Customer Profile</h3>
                    <p class="text-md mt-1 max-w-2xl text-gray-600">Details and informations about customer.</p>
                </div>
                <Link :href="route('admin.customers.index')" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700">Cancel</Link>
            </div>

            <div class="flex items-center gap-8">
                <!-- Customer Image -->
                <div class="flex flex-col w-1/2 items-center justify-center gap-5 bg-white">
                    <img
                        :src="customer?.image_url ?? '/male-profile-avatar.png'"
                        alt="Customer Image"
                        class="h-50 w-50 rounded-full object-cover shadow-md"
                    />
                    <div class="text-center">
                        <h1 class="text-2xl font-semibold">{{ customer.name }}</h1>
                        <p class="text-gray-500">{{ customer.email }}</p>
                        <p class="text-gray-500">{{ customer.customer_profile?.mobile ?? '' }}</p>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="w-full overflow-hidden bg-white border-l border-gray-200">
                    <!-- Customer Profile Details -->
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-md font-medium text-gray-500">Full name</dt>
                                <dd class="text-md mt-1 text-gray-900 sm:col-span-2 sm:mt-0">{{ customer.name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-md font-medium text-gray-500">Email</dt>
                                <dd class="text-md mt-1 text-gray-900 sm:col-span-2 sm:mt-0">{{ customer.email }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-md font-medium text-gray-500">Mobile</dt>
                                <dd class="text-md mt-1 text-gray-900 sm:col-span-2 sm:mt-0">{{ customer.customer_profile?.mobile ?? '' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-md font-medium text-gray-500">Address</dt>
                                <dd class="text-md mt-1 text-gray-900 sm:col-span-2 sm:mt-0">{{ customer.customer_profile?.address ?? '' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-md font-medium text-gray-500">District/City</dt>
                                <dd class="text-md mt-1 text-gray-900 sm:col-span-2 sm:mt-0">{{ customer.customer_profile?.district ?? '' }}</dd>
                            </div>
                            <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-md font-medium text-gray-500">Division/State</dt>
                                <dd class="text-md mt-1 text-gray-900 sm:col-span-2 sm:mt-0">{{ customer.customer_profile?.division ?? '' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-md font-medium text-gray-500">Postal Code</dt>
                                <dd class="text-md mt-1 text-gray-900 sm:col-span-2 sm:mt-0">{{ customer.customer_profile?.postal_code ?? '' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
