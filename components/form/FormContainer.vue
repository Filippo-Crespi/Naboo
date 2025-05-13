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
      (String(element.ID_Modulo ?? '').toLowerCase().includes(searchLower))
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
  forms.value = res.data
} catch (error) {
  toast.add({
    severity: "error",
    summary: "Errore",
    detail: (error as Error).message,
    life: 2500,
  });
}

const search = ref("");

</script>

<template>
  <Toast />
  <div class="w-full grid-form place-items-center justify-evenly mt-8 px-8">
    <span class="text-3xl font-bold col-span-3 place-self-start">Siamo felici di riverderti
      <span class="text-[#10b981]">{{ user != undefined ? user.Nome : "" }}</span></span>
    <InputText v-model="search" placeholder="Cerca un modulo per titolo o codice" class="col-span-3" />
    <FormCard v-for="form in formsSearch" :key="form.ID_Modulo" :form="form" @delete="(id) => deleteForm(id)"
      image="https://picsum.photos/600/400" />
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