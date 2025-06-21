<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthCardLayout.vue';
import AppHeader from '@/components/AppHeader.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
        <div class="bg-muted w-full h-screen">
    <!-- App Header -->
    <AppHeader />

    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
    </div>
</template>
