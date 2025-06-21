<script setup>
import FeaturedProduct from '@/components/FeaturedProduct.vue';
import TestimonialSlider from '@/components/TestimonialSlider.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

defineProps({
    featuredCars: Array,
});

const page = usePage();
</script>

<template>
    <GuestLayout>
        <!-- Head Section -->
        <Head title="Home" />

        <!-- Main Content -->
        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="bg-blue-600 py-10 text-white ">
                <div class="container mx-auto flex items-center gap-4">
                <div class="w-1/3">
                    <img src="/logo.png" alt="Hero Image" class="h-[430px] w-[450px] object-cover">
                </div>
                <div class="container mx-auto px-4 text-center w-2/3">
                    <h1 class="mb-6 font-bold text-6xl">Rent Your Perfect Ride Today</h1>
                    <p class="mb-8 text-xl text-gray-50">
                        Discover a wide range of well-maintained cars tailored for every journey — from weekend getaways to daily commutes. 
                        Use our advanced filters to quickly find your ideal vehicle by type, brand, and budget.
                    </p>
                    <Link :href="route('frontend.cars')" class="rounded-full bg-white px-6 py-3 font-semibold text-blue-600 shadow-md text-lg transition hover:bg-gray-200">
                        Start Your Renty Ride!
                    </Link>
                </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="bg-white py-16">
                <div class="container mx-auto px-4 text-center">
                    <h2 class="mb-5 text-3xl font-semibold">Why Choose Us?</h2>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div class="rounded-xl border p-6 shadow transition hover:shadow-lg">
                            <h3 class="mb-2 text-xl font-bold">Wide Range of Cars</h3>
                            <p>From economy to luxury – we’ve got you covered.</p>
                        </div>
                        <div class="rounded-xl border p-6 shadow transition hover:shadow-lg">
                            <h3 class="mb-2 text-xl font-bold">Quick Booking</h3>
                            <p>Book in minutes with our easy-to-use system.</p>
                        </div>
                        <div class="rounded-xl border p-6 shadow transition hover:shadow-lg">
                            <h3 class="mb-2 text-xl font-bold">Secure Payments</h3>
                            <p>Your data and transactions are safe with us.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Cars Section -->
            <FeaturedProduct :cars="page.props.featuredCars" />
        </main>

        <!-- Testimonials Section -->
        <TestimonialSlider />
    </GuestLayout>
</template>
