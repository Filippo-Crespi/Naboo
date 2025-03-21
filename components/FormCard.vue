<template>
  <Card>
    <template #header><NuxtImg :src="image" /></template>
    <template #title>{{ title }}</template>
    <template #subtitle>{{ subtitle }}</template>
    <template #content>
      <ButtonGroup>
        <Button icon="pi pi-pencil" @click="$emit('edit', id)" rounded />
        <Button icon="pi pi-trash" @click="$emit('delete', id)" rounded />
      </ButtonGroup>
    </template>
  </Card>
</template>

<script lang="ts" setup>
const props = defineProps({
  id: {
    type: Number,
    required: true,
  },
  title: {
    type: String,
    default: "Form title",
  },
  subtitle: {
    type: String,
    default: "Descrizione",
  },
  image: {
    type: String,
    default: "https://placehold.co/200x150",
  },
});
</script>

<style></style>
