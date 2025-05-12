<template>
  <div
    class="max-w-64 bg-white shadow-md rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300 ease-in-out">
    <div class="h-40">
      <NuxtImg :src="image" class="w-full h-full object-cover" />
    </div>
    <div class="p-4">
      <h3 class="text-lg font-semibold text-gray-800">{{ title }}</h3>
      <p class="text-sm text-gray-600 mt-2">{{ description }}</p>
      <div class="mt-4 flex gap-4">
        <Button as="router-link" icon="pi pi-pencil" :to="`/edit/${id}`" rounded />
        <Button icon="pi pi-trash" @click="showDeleteDialog = true" outlined severity="danger" />
      </div>
    </div>
    <Dialog v-model:visible="showDeleteDialog" pt:mask:class="backdrop-blur-sm" header="Conferma Eliminazione"
      :modal="true" :closable="false" :style="{ width: '30vw' }">
      <p>Sei sicuro di voler eliminare questo elemento?</p>
      <template #footer>
        <Button label="Annulla" icon="pi pi-times" @click="showDeleteDialog = false" severity="secondary" />
        <Button label="Conferma" icon="pi pi-check" @click="confirmDelete" severity="danger" />
      </template>
    </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';

const props = defineProps({
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
    default: "https://picsum.photos/600/400",
  },
});

const emit = defineEmits<{
  (e: 'delete', id: string): void;
}>();

const showDeleteDialog = ref(false);

function confirmDelete() {
  showDeleteDialog.value = false;
  emit('delete', props.id);
}
</script>

<style></style>