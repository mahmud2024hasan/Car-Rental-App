<script setup>
import { Link, usePage } from '@inertiajs/vue3';

// Access the current route
const page = usePage();

// Check if the given route is active
function isActive(routeName) {
    return route().current(routeName);
}
</script>

<template>
    <header class="bg-white p-4 shadow-md">
        <div class="container mx-auto flex flex-col items-center justify-between gap-4 md:flex-row">
            
            <!-- Logo -->
            <div class="text-2xl font-bold text-blue-700 flex items-center gap-4">
                <img src="/logo1.png" alt="Logo" class="h-16 w-16 rounded-sm">
                <Link :href="route('frontend.home')" class="text-red-600"><span class="text-purple-700">Renty</span>Ride</Link>
            </div>

            <!-- Navigation -->
            <nav class="flex flex-wrap items-center justify-center gap-2 text-lg font-semibold">
                <Link
                    :href="route('frontend.home')"
                    :class="[
                        'rounded-sm px-4 py-2',
                        isActive('frontend.home') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-600 hover:text-white'
                    ]"
                >Home</Link>

                <Link
                    :href="route('frontend.cars')"
                    :class="[
                        'rounded-sm px-4 py-2',
                        isActive('frontend.cars') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-600 hover:text-white'
                    ]"
                >Cars</Link>

                <Link
                    :href="route('frontend.about')"
                    :class="[
                        'rounded-sm px-4 py-2',
                        isActive('frontend.about') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-600 hover:text-white'
                    ]"
                >About</Link>

                <Link
                    :href="route('frontend.contact')"
                    :class="[
                        'rounded-sm px-4 py-2',
                        isActive('frontend.contact') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-600 hover:text-white'
                    ]"
                >Contact</Link>
            </nav>

            <!-- Auth Buttons -->
            <nav class="flex items-center justify-end gap-3">
                <template v-if="page.props.auth.user">
                    <Link
                        :href="page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('customer.dashboard')"
                        class="inline-block rounded-sm border border-gray-300 px-5 py-1.5 text-md font-medium text-blue-600 hover:bg-blue-600 hover:text-white"
                    >
                        Dashboard
                    </Link>
                </template>
                <template v-else>
                    <Link
                        :href="route('login')"
                        :class="[
                            'inline-block rounded-sm border px-5 py-1.5 text-sm font-medium',
                            isActive('login') ? 'bg-blue-600 text-white border-blue-600' : 'text-blue-600 hover:bg-blue-600 hover:text-white'
                        ]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        :class="[
                            'inline-block rounded-sm border px-5 py-1.5 text-sm font-medium',
                            isActive('register') ? 'bg-blue-600 text-white border-blue-600' : 'text-blue-600 hover:bg-blue-600 hover:text-white'
                        ]"
                    >
                        Register
                    </Link>
                </template>
            </nav>

        </div>
    </header>
</template>
