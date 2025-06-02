<template>
  <div :class="[
    'bg-white shadow-lg rounded-lg overflow-hidden hover:scale-105 transition-transform duration-300 ease-in-out p-4 flex flex-col justify-between border-b-4',
    form.anonymous ? 'border-gray-400' : 'border-[#10b981]'
  ]">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 truncate flex justify-between">
        <span>{{ form.title }}</span>
        <span class="text-[#10b981] flex items-center gap-2 cursor-pointer select-none"
          @click.stop="copyCodice(form.code ?? '')" title="Copia codice">
          <template v-if="!copied">
            <Icon class="text-xl" name="material-symbols:content-copy-outline-rounded" />
          </template>
          <template v-else>
            <span class="text-xs ml-1 text-[#10b981]">Codice copiato!</span>
          </template>
        </span>
      </h3>
      <p class="text-sm text-gray-600 mt-2 truncate">{{ form.description }}</p>
    </div>
    <div class="mt-6 flex justify-between gap-4">
      <div class="flex items-center gap-2">
        <Button as="router-link" :to="`/edit/${form.code}`" rounded>
          <Icon name="solar:pen-bold-duotone" />
        </Button>
        <Button severity="danger" @click="showDeleteDialog = true">
          <Icon name="solar:trash-bin-2-bold-duotone" />
        </Button>
      </div>
      <Button as="router-link" outlined severity="info" :to="`/report/${form.code}`" rounded>
        <Icon name="solar:documents-bold-duotone" class="text-[#10b981]" />
      </Button>
    </div>
    <Dialog v-model:visible="showDeleteDialog" pt:mask:class="backdrop-blur-sm" header="Conferma Eliminazione"
      :modal="true" :closable="false" class="w-5/6 md:w-[unset]">
      <p>Sei sicuro di voler eliminare questo elemento?</p>
      <template #footer>
        <div class=" w-full flex flex-col-reverse md:flex-row gap-4">
          <Button label="Annulla" icon="pi pi-times" @click="showDeleteDialog = false" severity="secondary" />
          <Button label="Conferma" icon="pi pi-check" @click="confirmDelete" severity="danger" />
        </div>
      </template>
    </Dialog>
  </div>
</template>



<script lang="ts" setup>
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
const copied = ref(false);
let copyTimeout: any = null;

function copyCodice(codice: string) {
  if (!codice) return;
  // Usa l'API Clipboard standard se disponibile
  if (navigator && navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(codice).then(() => {
      copied.value = true;
      if (copyTimeout) clearTimeout(copyTimeout);
      copyTimeout = setTimeout(() => copied.value = false, 1500);
    }, () => {
      fallbackCopyTextToClipboard(codice);
    });
  } else {
    fallbackCopyTextToClipboard(codice);
  }
}

function fallbackCopyTextToClipboard(text: string) {
  // Fallback per browser che non supportano navigator.clipboard
  const textArea = document.createElement('textarea');
  textArea.value = text;
  textArea.style.position = 'fixed';  // Evita scroll
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();
  try {
    document.execCommand('copy');
    copied.value = true;
    if (copyTimeout) clearTimeout(copyTimeout);
    copyTimeout = setTimeout(() => copied.value = false, 1500);
  } catch (err) {
    // Se anche il fallback fallisce, non mostrare nulla
  }
  document.body.removeChild(textArea);
}

function confirmDelete() {
  showDeleteDialog.value = false;
  if (props.form.id_form)
    emit('delete', String(props.form.id_form));
}
</script>

<style></style>