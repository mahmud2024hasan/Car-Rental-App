<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <GuestLayout>
        <!-- Head Section -->
        <Head title="Contact" />

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-12">
            <h1 class="text-4xl font-bold text-center text-blue-700 mb-4">Get in Touch</h1>
            <p class="text-center text-gray-600 max-w-2xl mx-auto mb-10">
                We’re here to help! Whether you have questions, feedback, or need assistance with a booking — don’t hesitate to reach out.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-xl font-semibold text-blue-600 mb-2">Contact Information</h2>
                    <p class="text-gray-700 mb-2">📍 123 RentyRide Street, Dhaka, Bangladesh</p>
                    <p class="text-gray-700 mb-2">📞 +880 123 456 789</p>
                    <p class="text-gray-700">✉️ support@rentyride.com</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-blue-600 mb-2">Business Hours</h2>
                    <ul class="text-gray-700">
                        <li>Monday - Friday: 9:00 AM – 6:00 PM</li>
                        <li>Saturday: 10:00 AM – 4:00 PM</li>
                        <li>Sunday: Closed</li>
                    </ul>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>