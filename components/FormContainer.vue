<template>
  <div class="flex gap-4 flex-wrap">
    <FormCard
      v-for="form of forms"
      :key="form.id"
      :id="form.id"
      :title="form.title"
      :subtitle="form.description" />
  </div>
</template>

<script lang="ts" setup>
const forms = ref([
  {
    id: 1,
    title: "Form 1",
    description: "Description for Form 1",
  },
  {
    id: 2,
    title: "Form 2",
    description: "Description for Form 2",
  },
]);
</script>

<style></style>
