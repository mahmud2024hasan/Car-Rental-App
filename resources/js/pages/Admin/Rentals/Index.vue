<script setup>
import FlashMessage from '@/components/custom/FlashMessage.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import FormatNumber from '@components/custom/FormatNumber.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { ListCollapse, Plus, SquarePen, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const breadcrumbs = [
    {
        title: 'Manage Rentals',
        href: '/admin/rentals',
    },
];

// Define the props for accessing data
const props = defineProps({
    rentals: Array,
    filters: Object,
});

// Retrieve the rentals data and filters from props
const rentals = computed(() => props.rentals.data);
const search = ref(props.filters.search || '');

// Debounced search function to avoid too many requests
const submitSearch = debounce(() => {
    router.get(route('admin.rentals.index'), { search: search.value.trim() }, { preserveState: true, replace: true });
}, 300); // Wait for 300 milliseconds before searching

// Triggered whenever the user types in the search input
watch(search, () => {
    submitSearch();
});

// Function to delete a rental
function deleteRental(rentalId) {
    if (confirm('Are you sure you want to delete this rental?')) {
        router.delete(route('admin.rentals.destroy', rentalId), {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <Head title="Manage Rentals" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
            <!-- Content Header Section -->
            <div class="flex items-center justify-between bg-gradient-to-b from-blue-500 via-blue-200 to-blue-100 px-5 py-10">
                <div class="flex w-1/4 justify-start">
                    <h1 class="text-3xl font-semibold">All Rentals</h1>
                </div>

                <!-- Search Area -->
                <div class="flex w-2/4 items-center justify-center gap-4">
                    <div class="relative w-2/3">
                        <input
                            v-model="search"
                            type="text"
                            class="h-12 w-full rounded-md border bg-white p-4 shadow-lg focus:ring-1 focus:ring-blue-500 focus:outline-none"
                            placeholder="Search by customer, car, start date, end date, status"
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
                        :href="route('admin.rentals.create')"
                        class="text-md inline-flex cursor-pointer items-center rounded bg-blue-600 px-3 py-2 font-semibold text-white shadow-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                        aria-label="Add New Rental"
                    >
                        <Plus class="mr-1 h-6 w-6" /> Add New Rental
                    </Link>
                </div>
            </div>

            <!-- Show flash message here -->
            <FlashMessage />

            <!-- Rentals Table Section -->
            <div class="mx-8 my-4 overflow-x-auto rounded bg-white shadow-lg">
                <div v-if="rentals.length > 0">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="border-t bg-gray-100">
                                <th class="px-4 py-2">SL</th>
                                <th class="px-4 py-2">Customer Details</th>
                                <th class="px-4 py-2">Car Details</th>
                                <th class="px-4 py-2">Start Date</th>
                                <th class="px-4 py-2">End Date</th>
                                <th class="px-4 py-2">Total Cost</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(rental, index) in rentals" :key="rental.id" class="border-b bg-white text-center hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    {{ index + 1 + (props.rentals.current_page - 1) * props.rentals.per_page }}
                                </td>

                                <!-- Customer Info with Image -->
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <img
                                            class="h-16 w-16 rounded-full object-cover mr-1"
                                            :src="rental.customer_details.image_url || '/male-profile-avatar.png'"
                                            :alt="rental.customer_details.name"
                                        />
                                        <div class="text-left">
                                            <span class="font-semibold">{{ rental.customer_details.name }}</span
                                            ><br />
                                            <span class="text-sm text-gray-700">
                                                Email: {{ rental.customer_details.email }} <br />
                                                <template v-if="rental.customer_details.mobile">
                                                    Mobile: {{ rental.customer_details.mobile }}
                                                </template>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Car Info with Image -->
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <img
                                            class="h-16 w-20 rounded-sm object-cover mr-1"
                                            :src="rental.car_details.image_url || '/logo.png'"
                                            :alt="rental.car_details.name"
                                        />
                                        <div class="text-left">
                                            <span class="font-semibold">{{ rental.car_details.name }}</span
                                            ><br />
                                            <span class="text-sm text-gray-700">
                                                Brand: {{ rental.car_details.brand }}, Model: {{ rental.car_details.model }} <br /> 
                                                Type: {{ rental.car_details.car_type }}, Daily Rent: <FormatNumber :number="rental.car_details.daily_rent_price" />
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-2">{{ rental.start_date }}</td>
                                <td class="px-4 py-2">{{ rental.end_date }}</td>
                                <td class="px-4 py-2">
                                    <FormatNumber :number="rental.total_cost" />
                                </td>
                                <td class="px-4 py-2">{{ rental.status }}</td>

                                <!-- Actions -->
                                <td class="px-4 py-2">
                                    <Link
                                        :href="route('admin.rentals.show', rental.id)"
                                        class="mr-4 inline-flex items-center text-blue-600 hover:underline"
                                    >
                                        <ListCollapse class="h-5 w-5" />
                                    </Link>
                                    <Link
                                        :href="route('admin.rentals.edit', rental.id)"
                                        class="mr-4 inline-flex items-center text-green-700 hover:underline"
                                    >
                                        <SquarePen class="h-5 w-5" />
                                    </Link>
                                    <button
                                        @click="deleteRental(rental.id)"
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
                    Sorry! 😕 No results found for "<strong>{{ search }}</strong
                    >"
                </div>

                <!-- No Rentals Available -->
                <div v-else class="py-8 text-center text-gray-500">🚗 No rentals available yet.</div>

                <!-- Pagination Links -->
                <div v-if="!search" class="mt-4 mb-4 flex justify-center gap-1">
                    <template v-for="link in props.rentals.links" :key="link.label">
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
