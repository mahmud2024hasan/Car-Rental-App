<script setup>
const props = defineProps({
  number: {
    type: [Number, String],
    required: true,
  },
});

function formatNumber(amount) {
  const number = parseFloat(amount);
  if (isNaN(number)) return ''; 
  const options = {
    style: 'decimal',
    maximumFractionDigits: number % 1 === 0 ? 0 : 2,
  };
  return '৳ ' + number.toLocaleString('en-IN', options);
}
</script>

<template>
  <span v-if="!isNaN(parseFloat(props.number))">
    {{ formatNumber(props.number) }}
  </span>
</template>
