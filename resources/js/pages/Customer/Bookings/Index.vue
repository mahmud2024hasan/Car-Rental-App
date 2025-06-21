<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import FlashMessage from '@/components/custom/FlashMessage.vue';
import FormatNumber from '@components/custom/FormatNumber.vue';
import FormatDate from '@components/custom/FormatDate.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const { rentals } = defineProps({ rentals: Array });

const breadcrumbs = [
    { title: 'My Bookings', href: '/customer/bookings' },
];

// Filter tabs
const activeTab = ref('all');

const filteredRentals = computed(() => {
    const now = new Date();

    return rentals
        .map((rental) => {
            const start = new Date(rental.start_date);
            const end = new Date(rental.end_date);

            const total_days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

            let matchesTab = false;

            switch (activeTab.value) {
                case 'completed':
                    matchesTab = rental.status === 'Completed';
                    break;
                case 'ongoing':
                    matchesTab = rental.status === 'Ongoing';
                    break;
                case 'upcoming':
                    matchesTab = ['Pending', 'Confirmed'].includes(rental.status) && start > now;
                    break;
                case 'canceled':
                    matchesTab = rental.status === 'Canceled';
                    break;
                default:
                    matchesTab = true;
            }

            return matchesTab ? { ...rental, total_days } : null;
        })
        .filter(Boolean);
});

function setTab(tab) {
    activeTab.value = tab;
}

function cancelBooking(id) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        router.delete(route('customer.bookings.cancel', id));
    }
}
</script>

<template>
    <Head title="My Bookings" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto">
            <!-- Show flash message here -->
            <div class="mt-4"><FlashMessage /></div>

            <!-- Content Header -->
            <div class="flex items-center justify-between p-2 mt-4">
                <h1 class="text-2xl font-bold text-blue-700">My Bookings History</h1>

                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-4">
                    <button
                        @click="setTab('all')"
                        :class="activeTab === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                        class="text-md rounded px-4 py-2 font-medium hover:bg-blue-700 hover:text-white cursor-pointer"
                    >
                        All
                    </button>
                    <button
                        @click="setTab('upcoming')"
                        :class="activeTab === 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                        class="text-md rounded px-4 py-2 font-medium hover:bg-blue-700 hover:text-white cursor-pointer"
                    >
                        Upcoming
                    </button>
                    <button
                        @click="setTab('ongoing')"
                        :class="activeTab === 'ongoing' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                        class="text-md rounded px-4 py-2 font-medium hover:bg-blue-700 hover:text-white cursor-pointer"
                    >
                        Ongoing
                    </button>
                    <button
                        @click="setTab('completed')"
                        :class="activeTab === 'completed' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                        class="text-md rounded px-4 py-2 font-medium hover:bg-blue-700 hover:text-white cursor-pointer"
                    >
                        Completed
                    </button>
                    <button
                        @click="setTab('canceled')"
                        :class="activeTab === 'canceled' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                        class="text-md rounded px-4 py-2 font-medium hover:bg-blue-700 hover:text-white cursor-pointer"
                    >
                        Canceled
                    </button>
                </div>
            </div>
            
            <!-- Booking Table -->
            <div v-if="filteredRentals.length > 0" class="overflow-x-auto shadow-md mb-8">
                <table class="text-md min-w-full border border-gray-200 bg-white rounded-md">
                    <thead class="bg-gray-100 text-left">
                        <tr class="text-center text-gray-700">
                            <th class="border-b px-4 py-2">SL</th>
                            <th class="border-b px-4 py-2">Car Image</th>
                            <th class="border-b px-4 py-2">Car Details</th>
                            <th class="border-b px-4 py-2">Start Date</th>
                            <th class="border-b px-4 py-2">End Date</th>
                            <th class="border-b px-4 py-2">Total Days</th>
                            <th class="border-b px-4 py-2">Total Cost</th>
                            <th class="border-b px-4 py-2">Status</th>
                            <th class="border-b px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(rental, index) in filteredRentals" :key="rental.id" class="text-md text-center hover:bg-gray-50">
                            <td class="border-b px-4 py-2">{{ index + 1 }}</td>
                            <td class="flex justify-center border-b px-4 py-2">
                                <img :src="rental.car_image" alt="Car Image" class="h-20 w-26 rounded-md object-cover" />
                            </td>
                            <td class="border-b px-4 py-2">
                                <div class="font-semibold">{{ rental.car.name }}</div>
                                <div class="text-sm text-gray-500">{{ rental.car.brand }} - {{ rental.car.model }}</div>
                            </td>
                            <td class="border-b px-4 py-2"><FormatDate :date="rental.start_date" /></td>
                            <td class="border-b px-4 py-2"><FormatDate :date="rental.end_date" /></td>
                            <td class="border-b px-4 py-2">{{ rental.total_days }} days</td>
                            <td class="border-b px-4 py-2"><FormatNumber :number="rental.total_cost" /></td>
                            <td class="border-b px-4 py-2">
                                <span
                                    class="inline-block rounded px-2 py-1 text-sm font-semibold"
                                    :class="{
                                        'bg-red-100 text-red-700': rental.status === 'Canceled',
                                        'bg-green-100 text-green-700': rental.status === 'Completed',
                                        'bg-yellow-100 text-yellow-800':
                                            ['Pending', 'Confirmed'].includes(rental.status) && new Date(rental.start_date) > new Date(),
                                        'bg-blue-100 text-blue-700': rental.status === 'Ongoing',
                                    }"
                                >
                                    {{ rental.status }}
                                </span>
                            </td>

                            <td class="border-b px-4 py-2">
                                <button
                                    v-if="rental.is_cancellable"
                                    @click="cancelBooking(rental.id)"
                                    class="cursor-pointer rounded bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700"
                                >
                                    Cancel
                                </button>
                                <span v-else class="text-sm text-gray-400">Not allowed</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else class="mt-6 text-gray-500">No bookings found for this filter.</div>
        </div>
    </AppLayout>
</template>
