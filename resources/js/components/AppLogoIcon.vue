<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { Car } from 'lucide-vue-next';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img src="/logo1.png" alt="Car Icon" class="h-14 w-14" />
</template>
