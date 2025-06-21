<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

defineProps<{
    title?: string;
    description?: string;
}>();

// Get the message from the query string
const query = new URLSearchParams(window.location.search);
const messageKey = query.get('message');
let message = '';

if (messageKey === 'login_required') {
    message = 'Please login first to book a car.';
} else if (messageKey === 'unauthorized') {
    message = 'Unauthorized role, please login as customer or admin.';
}
</script>

<template>
        <div class="flex w-full max-w-md flex-col gap-6 mx-auto mt-24">
            <!-- Flash Message -->
            <div v-if="message" class="w-full mx-auto mt-20 rounded-r border-l-8 border-l-red-500 bg-white p-3 text-lg text-red-600 font-semibold shadow-md">
                {{ message }}
            </div>

            <div class="w-full flex flex-col gap-4">
                <Card class="rounded-xl">
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <CardTitle class="text-xl">{{ title }}</CardTitle>
                        <CardDescription>
                            {{ description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="px-10">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>

</template>
