<script setup>
const val = ref("");
</script>

<template>
  <div class="p-10">
    <UInput v-model="val" />
    {{ val }}
  </div>
</template>
