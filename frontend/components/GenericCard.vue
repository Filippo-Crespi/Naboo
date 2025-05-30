<template>
  <div
    class="card flex flex-col gap-6 items-start justify-start rounded-2xl shadow-lg p-6 max-w-100 bg-white">
    <span class="text-4xl">{{ emoji }}</span>
    <div class="flex flex-col gap-1">
      <span class="text-xl font-bold">{{ title }}</span>
      <span class="opacity-50">{{ description }}</span>
    </div>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  title: { type: String, default: "Titolo" },
  description: { type: String, default: "Descrizione" },
  emoji: { type: String, default: "❤️" },
});
</script>

<style scoped>
.card:hover {
  transform: rotate(1deg);
  transition: all 0.4s ease-in-out;
}
</style>
