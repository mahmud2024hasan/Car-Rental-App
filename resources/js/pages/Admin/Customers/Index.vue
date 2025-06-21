<script setup>
import FlashMessage from '@/components/custom/FlashMessage.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { ListCollapse, Plus, SquarePen, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

// Define the props for accessing customer data
const props = defineProps({
    customers: Object,
    filters: Object,
});
const customers = computed(() => props.customers.data);

// Breadcrumbs for navigation
const breadcrumbs = [
    {
        title: 'Manage Customers',
        href: '/admin/customers',
    },
];

// Search functionality
const search = ref(usePage().props.filters?.search ?? '');

// Debounced search function to avoid too many requests
const submitSearch = debounce(() => {
    router.get(route('admin.customers.index'), { search: search.value }, { preserveState: true, replace: true });
}, 300); // Wait for 300 milliseconds before searching

// Triggered whenever the user types in the search input
watch(search, () => {
    submitSearch();
});

// Delete customer function
function deleteCustomer(customerId) {
    if (confirm('Are you sure you want to delete this customer?')) {
        router.delete(route('admin.customers.destroy', customerId));
    }
}
</script>

<template>
    <Head title="Manage Customers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
            
            <!-- Content Header Section -->
            <div class="flex items-center justify-between bg-gradient-to-b from-blue-500 via-blue-200 to-blue-100 px-5 py-10">
                <div class="flex w-1/4 justify-start">
                    <h1 class="text-3xl font-semibold">All Customers</h1>
                </div>

                <!-- Search Area -->
                <div class="flex w-2/4 items-center justify-center gap-4">
                    <div class="relative w-2/3">
                        <input
                            v-model="search"
                            type="text"
                            class="h-12 w-full rounded-md border bg-white p-4 shadow-lg focus:ring-1 focus:ring-blue-500 focus:outline-none"
                            placeholder="Search by customer name, mobile, email"
                            @keyup.enter="submitSearch"
                        />
                        <div class="absolute top-0 right-0 h-full w-12 rounded-r-md bg-blue-600 text-white" aria-label="Search">
                            <svg
                                class="absolute top-3.5 right-3 h-5 w-5 fill-current text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                version="1.1"
                                x="0px"
                                y="0px"
                                viewBox="0 0 56.966 56.966"
                                style="enable-background: new 0 0 56.966 56.966"
                                xml:space="preserve"
                            >
                                <path
                                    d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex w-1/4 justify-end">
                    <Link
                        :href="route('admin.customers.create')"
                        class="text-md inline-flex cursor-pointer items-center rounded bg-blue-600 px-3 py-2 font-semibold text-white shadow-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                        aria-label="Add New Customer"
                    >
                        <Plus class="mr-1 h-6 w-6" /> Add New Customer
                    </Link>
                </div>
            </div>

            <!-- Show flash message here -->
            <FlashMessage />
            
            <!-- Customers Table -->
            <div class="mx-8 mt-4 overflow-x-auto rounded bg-white shadow-lg">
                <div v-if="customers.length > 0">
                    <table class="w-full table-auto">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-center">SL</th>
                                <th class="px-4 py-2 text-center">Image</th>
                                <th class="px-4 py-2 text-center">Customer Name</th>
                                <th class="px-4 py-2 text-center">Email</th>
                                <th class="px-4 py-2 text-center">Mobile</th>
                                <th class="px-4 py-2 text-center">Street Address</th>
                                <th class="px-4 py-2 text-center">District</th>
                                <th class="px-4 py-2 text-center">Division</th>
                                <th class="px-4 py-2 text-center">Postal Code</th>
                                <th class="px-4 py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(customer, index) in customers" :key="customer.customer_id" class="border-b hover:bg-gray-50 text-center">
                                <td class="px-4 py-2 text-center">{{ index + 1 + (props.customers.current_page - 1) * props.customers.per_page }}</td>
                                <td class="px-4 py-2 text-center">
                                    <img :src="customer.image_url ?? '/male-profile-avatar.png'" alt="Customer Image" class="h-16 w-16 rounded-full object-cover" />
                                </td>
                                <td class="px-4 py-2 text-center font-semibold">{{ customer.name }}</td>
                                <td class="px-4 py-2 text-center">{{ customer.email }}</td>
                                <td class="px-4 py-2 text-center">{{ customer.profile?.mobile ?? '' }}</td>
                                <td class="px-4 py-2 text-center">{{ customer.profile?.address ?? '' }}</td>
                                <td class="px-4 py-2 text-center">{{ customer.profile?.district ?? '' }}</td>
                                <td class="px-4 py-2 text-center">{{ customer.profile?.division ?? '' }}</td>
                                <td class="px-4 py-2 text-center">{{ customer.profile?.postal_code ?? '' }}</td>
                                <td class="text-center p-2 w-[150px]">
                                    <Link
                                        :href="route('admin.customers.show', customer.customer_id)"
                                        class="mr-4 inline-flex items-center text-blue-600 hover:underline"
                                    >
                                        <ListCollapse class="h-5 w-5" />
                                    </Link>
                                    <Link
                                        :href="route('admin.customers.edit', customer.customer_id)"
                                        class="mr-4 inline-flex items-center text-green-700 hover:underline"
                                    >
                                        <SquarePen class="h-5 w-5" />
                                    </Link>
                                    <button
                                        @click="deleteCustomer(customer.customer_id)"
                                        class="inline-flex cursor-pointer items-center text-red-600 hover:underline"
                                    >
                                        <Trash2 class="h-5 w-5" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- No Search Results -->
                <div v-else-if="search" class="py-8 text-center text-gray-500">
                    😕 No results found for "<strong>{{ search }}</strong
                    >"
                </div>

                <!-- No Rentals Available -->
                <div v-else class="py-8 text-center text-gray-500">🚗 No rentals available yet.</div>

                <!-- Pagination Links -->
                <div v-if="!search" class="my-4 flex justify-center gap-1">
                    <template v-for="link in props.customers.links" :key="link.label">
                        <Link
                            :href="link.url"
                            v-html="link.label"
                            class="rounded border px-3 py-1"
                            :class="{
                                'pointer-events-none bg-gray-300 text-black': !link.url,
                                'bg-green-600 text-white': link.active,
                            }"
                        />
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
