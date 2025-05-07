<template>
  <Card class="max-w-64 hover:scale-105 transition-transform duration-300 ease-in-out">
    <template #header>
      <NuxtImg :src="image" />
    </template>
    <template #title>{{ title }}</template>
    <template #subtitle>{{ description }}</template>
    <template #content>
      <ButtonGroup>
        <Button icon="pi pi-pencil" @click="$emit('edit', id)" rounded />
        <Button icon="pi pi-trash" @click="$emit('delete', id)" rounded />
      </ButtonGroup>
    </template>
  </Card>
</template>

<script lang="ts" setup>
defineProps({
  id: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    default: "Form title",
  },
  description: {
    type: String,
    default: "Descrizione",
  },
  image: {
    type: String,
    default: "https://placehold.co/600x400",
  },
});
</script>

<style></style>