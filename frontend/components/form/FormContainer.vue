<script lang="ts" setup>
import type { Form, Response, User } from '~/types';

const emit = defineEmits(['refresh']);

// @ts-ignore
const user = useCookie("user").value as User;
const session_uuid = useCookie("session_uuid").value;

const toast = useToast();

const forms = ref<Form[]>();
const formsSearch = computed(() => {
  if (!forms.value) return [];
  if (!search.value) return forms.value;
  const searchLower = search.value.toLowerCase();
  return (forms.value).filter((element: Form) => {
    return (
      (element.title?.toLowerCase().includes(searchLower) ?? false) ||
      (String(element.code ?? '').toLowerCase().includes(searchLower))
    );
  });
});

const deleteForm = async (id: String) => {
  try {
    await useFetch("http://localhost:8085/api/moduli/utenti.php?" + "session_uuid=" + session_uuid + "&id_form=" + id, {
      method: "DELETE",
      onResponseError({ response }) {
        if (response.status === 404) {
          throw new Error(response._data.message);
        } else if (response.status === 500) {
          throw new Error("Errore del server");
        }
      },
    });
    toast.add({
      severity: "success",
      summary: "Successo",
      detail: "Modulo eliminato con successo.",
      life: 2500,
    });
    emit('refresh');
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 2500,
    });
  }
}

const search = ref("");
const loading = ref(true);

onMounted(async () => {
  loading.value = true;
  try {
    const res: Response = await $fetch("http://localhost:8085/api/moduli/utenti.php?session_uuid=" + session_uuid, {
      key: "forms",
      method: "GET",
      onResponseError({ response }) {
        if (response.status === 404) {
          throw new Error(response._data?.message || "Nessun modulo trovato.");
        } else if (response.status === 500) {
          throw new Error("Errore del server. Riprova più tardi.");
        }
      },
    });
    forms.value = res.data;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message || "Impossibile caricare i moduli. Riprova più tardi.",
      life: 2500,
    });
    // Fallback: lista vuota
    forms.value = [];
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <Toast />
  <div v-if="loading" class="flex flex-col items-center justify-center text-lg min-h-[200px]">
    <svg class="animate-spin h-8 w-8 text-[#10b981] mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
      viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
    Caricamento moduli...
  </div>
  <div v-else>
    <div v-if="!forms" class="flex flex-col items-center justify-center text-lg min-h-[200px]">
      <Icon class="text-6xl mb-4 text-[#10b981]" name="solar:square-alt-arrow-up-bold-duotone" />
      <p class="text-gray-500">Nessun modulo trovato.</p>
      <p class="text-gray-500">Crea il tuo primo modulo!</p>
    </div>
    <div v-else class="w-full grid-form  place-items-center justify-evenly mt-8 px-8">
      <span class="text-2xl text-center md:text-left  md:text-4xl font-bold col-span-3 place-self-start">Siamo felici di
        riverderti
        <span class="text-[#10b981]">{{ user != undefined ? user.first_name : "" }}</span></span>
      <InputText v-model="search" placeholder="Cerca un modulo per titolo o codice" class="col-span-3" />
      <div class="max-w-full col-span-3 flex flex-col gap-8 md:grid md:grid-cols-3">
        <FormCard v-for="form in formsSearch" :key="form.id_form" :form="form" @delete="(id) => deleteForm(id)"
          image="https://picsum.photos/600/400" />
      </div>
    </div>

  </div>
</template>

<style scoped>
.grid-form {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  justify-items: stretch;
  gap: 2rem;
}
</style>