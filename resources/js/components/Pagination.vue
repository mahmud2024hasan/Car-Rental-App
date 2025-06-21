<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  links: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <div class="mt-10 flex justify-center gap-1">
    <template v-for="link in links" :key="link.label">
      <component
        :is="link.url ? Link : 'span'"
        :href="link.url"
        v-html="link.label"
        class="rounded-md border px-4 py-2 text-md"
        :class="{
          'pointer-events-none bg-gray-200 text-gray-500': !link.url,
          'bg-blue-600 text-white font-semibold': link.active,
          'hover:bg-blue-500 hover:text-white transition': link.url && !link.active,
        }"
      />
    </template>
  </div>
</template>


