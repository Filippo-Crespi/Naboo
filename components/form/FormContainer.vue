<script lang="ts" setup>
import type { Form, Response, User } from "~/types";

const emit = defineEmits(['refresh']);

// @ts-ignore
const user = useCookie("user").value as User;
const token = useCookie("token").value;

const toast = useToast();

const forms = ref<Form[]>();
const formsSearch = computed(() => {
  if (!forms.value) return [];
  if (!search.value) return forms.value;
  const searchLower = search.value.toLowerCase();
  return (forms.value).filter((element: Form) => {
    return (
      (element.Titolo?.toLowerCase().includes(searchLower) ?? false) ||
      (String(element.Codice ?? '').toLowerCase().includes(searchLower))
    );
  });
});

const deleteForm = async (id: String) => {
  try {
    const res: Response = await $fetch("https://andrellaveloise.it/forms/users?" + "Token=" + token + "&ID_Modulo=" + id, {
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
      detail: res.message,
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
    const res: Response = await $fetch("https://andrellaveloise.it/forms/users?Token=" + token, {
      method: "GET",
      onResponseError({ response }) {
        if (response.status === 404) {
          throw new Error(response._data.message);
        } else if (response.status === 500) {
          throw new Error("Internal Server Error");
        }
      },
    });
    forms.value = res.data;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 2500,
    });
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
  <div v-else class="w-full grid-form  place-items-center justify-evenly mt-8 px-8">
    <span class="text-2xl text-center md:text-left  md:text-4xl font-bold col-span-3 place-self-start">Siamo felici di
      riverderti
      <span class="text-[#10b981]">{{ user != undefined ? user.Nome : "" }}</span></span>
    <InputText v-model="search" placeholder="Cerca un modulo per titolo o codice" class="col-span-3" />
    <div class="max-w-full col-span-3 flex flex-col gap-8 md:grid md:grid-cols-3">
      <FormCard v-for="form in formsSearch" :key="form.ID_Modulo" :form="form" @delete="(id) => deleteForm(id)"
        image="https://picsum.photos/600/400" />
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