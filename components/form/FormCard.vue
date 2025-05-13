<template>
  <div
    class="bg-white shadow-lg rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300 ease-in-out p-4 flex flex-col justify-between border-b-4 border-[#10b981]">
    <!-- <div>
      <NuxtImg :src="image" class="w-full h-full object-cover" />
    </div> -->
    <div>
      <h3 class="text-lg font-semibold text-gray-800 truncate flex justify-between">
        <span>{{ form.Titolo }}</span>
        <span v-if="isSupported" class="text-[#10b981] flex items-center gap-2 cursor-pointer select-none"
          @click="copy(form.ID_Modulo ?? '')">
          <span v-if="!copied" class="text-xs ml-1">
            <Icon name="material-symbols:content-copy-outline-rounded" />
          </span>
          <span v-else class="text-xs ml-1 text-[#10b981]">Copiato!</span>
          <span class="hover:cursor-pointer ml-2 text-xl">{{ form.ID_Modulo }}</span>
        </span>
        <span v-else class="text-[#10b981]">{{ form.ID_Modulo }}</span>
      </h3>
      <p class="text-sm text-gray-600 mt-2 truncate">{{ form.Descrizione }}</p>
    </div>
    <div class="mt-4 flex gap-4">
      <Button as="router-link" icon="pi pi-pencil" :to="`/edit/${form.ID_Modulo}`" rounded />
      <Button icon="pi pi-trash" @click="showDeleteDialog = true" outlined severity="danger" />
    </div>
    <Dialog v-model:visible="showDeleteDialog" pt:mask:class="backdrop-blur-sm" header="Conferma Eliminazione"
      :modal="true" :closable="false" :style="{ width: '30vw' }">
      <p>Sei sicuro di voler eliminare questo elemento?</p>
      <template #footer>
        <div class="w-full flex gap-4">
          <Button label="Annulla" icon="pi pi-times" @click="showDeleteDialog = false" severity="secondary" />
          <Button label="Conferma" icon="pi pi-check" @click="confirmDelete" severity="danger" />
        </div>
      </template>
    </Dialog>
  </div>
</template>



<script lang="ts" setup>
import { useClipboard } from '@vueuse/core';
import type { Form } from "../../types"

const props = defineProps({
  form: {
    type: Object as PropType<Form>,
    required: true,
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
const { text: copiedId, copy, copied, isSupported } = useClipboard();

function confirmDelete() {
  showDeleteDialog.value = false;
  if (props.form.ID_Modulo)
    emit('delete', props.form.ID_Modulo);
}
</script>

<style></style>