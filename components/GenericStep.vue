<template>
  <div class="flex flex-col gap-4 items-center justify-center max-w-100 min-w-20">
    <div
      class="flex items-center justify-center text-center px-8 py-4 text-2xl rounded-full font-medium bg-cyan-100 aspect-square">
      {{ step }}
    </div>
    <span class="font-bold text-2xl text-center">{{ title }}</span>
    <span class="text-center">{{ description }}</span>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  step: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
});
</script>

<style scoped></style>
