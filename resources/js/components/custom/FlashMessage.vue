<script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div v-if="page.props.flash.success" class="rounded-r border-l-8 border-l-green-500 bg-green-100 p-3 text-lg text-green-800 mx-2">
        {{ page.props.flash.success }}
    </div>
    <div v-if="page.props.flash.error" class="rounded-r border-l-8 border-l-red-500 bg-red-100 p-3 text-lg text-red-800">
        {{ page.props.flash.error }}
    </div>
</template>
    