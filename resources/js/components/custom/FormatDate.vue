<script setup>
import { computed } from 'vue';

const props = defineProps({
  date: {
    type: String,
    required: true,
  },
});

// Convert the date to a formatted string
const formatDate = computed(() => {
  if (!props.date) return '';

  const date = new Date(props.date);
  const options = { day: '2-digit', month: 'long', year: 'numeric' };
  return date.toLocaleDateString('en-GB', options);
});
</script>

<template>
  <span>{{ formatDate }}</span>
</template>
