<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <GuestLayout>
        <!-- Head Section -->
        <Head title="About" />

        <!-- Main Content -->
        <div class="mx-auto py-12">
            <div class="container mx-auto">
                <h1 class="mb-4 text-center text-4xl font-bold text-blue-700">About RentyRide</h1>
                <p class="mx-auto mb-10 max-w-7xl text-justify text-gray-600 text-lg">
                    <span class="font-semibold">RentyRide: Your Smart Car Rental Companion</span> <br />
                    RentyRide is a modern, user-friendly car rental platform designed to simplify how people rent vehicles. Whether you're planning a weekend getaway, a business trip, or need a reliable ride for daily use, RentyRide connects you with a wide range of well-maintained cars at competitive prices. Our platform offers an easy booking process, real-time availability, flexible pickup and drop-off times, and transparent pricing with no hidden charges. 
                    <br/>
                    With features like advanced car filtering (by type, brand, and price), responsive support, and seamless user experience, RentyRide ensures both convenience and satisfaction. Customers can search and book vehicles effortlessly, while admins manage rentals and car listings through a dedicated dashboard. Safety,cleanliness, and customer comfort are our top priorities. Whether you're an individual, a corporate traveler, or a family, RentyRide offers the perfect ride for your needs — quickly, affordably, and confidently. Let RentyRide be your trusted travel partner on the road!
                </p>
            </div>
            <section class="mb-16 bg-gray-50">
                <div class="container mx-auto grid grid-cols-2 gap-8 p-8">
                    <div class="">
                        <h2 class="mb-2 text-2xl font-semibold text-blue-600">Who We Are</h2>
                        <p class="text-gray-700">
                            Founded by a team of passionate tech and travel enthusiasts, RentyRide aims to redefine urban and intercity travel by
                            providing a reliable fleet of cars with hassle-free booking. We partner with verified vehicle owners and ensure all cars
                            go through strict quality checks.
                        </p>
                    </div>

                    <div>
                        <h2 class="mb-2 text-2xl font-semibold text-blue-600">What Makes Us Different</h2>
                        <ul class="list-inside list-disc text-gray-700">
                            <li>Instant booking with real-time availability</li>
                            <li>Transparent pricing — no hidden charges</li>
                            <li>24/7 customer support for peace of mind</li>
                            <li>Diverse vehicle options from compact to luxury</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Accordion FAQ -->
            <section>
                <h2 class="mb-6 text-center text-2xl font-semibold text-blue-700">Frequently Asked Questions</h2>
                <div class="mx-auto max-w-2xl space-y-4">
                    <details class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <summary class="cursor-pointer font-medium text-blue-700">How do I book a car on RentyRide?</summary>
                        <p class="mt-2 text-gray-700">
                            Simply browse available cars, select your preferred one, and complete the booking form. No login is needed unless you're a
                            returning customer with perks.
                        </p>
                    </details>

                    <details class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <summary class="cursor-pointer font-medium text-blue-700">Do I need a driving license?</summary>
                        <p class="mt-2 text-gray-700">
                            Yes, a valid driving license is mandatory. You’ll be asked to provide it at the time of handover.
                        </p>
                    </details>

                    <details class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <summary class="cursor-pointer font-medium text-blue-700">Are fuel charges included?</summary>
                        <p class="mt-2 text-gray-700">
                            No, fuel costs are excluded. You receive a full tank and are expected to return the car similarly fueled.
                        </p>
                    </details>
                </div>
            </section>
        </div>
    </GuestLayout>
</template>
