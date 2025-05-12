<script lang="ts" setup>
import type { Response, User } from "~/types";

const emit = defineEmits(['refresh']);

// @ts-ignore
const user = useCookie("user").value as User;
const token = useCookie("token").value;

const toast = useToast();

const forms = ref();

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


</script>

<template>
  <Toast />
  <div class="!p-8">
    <span class="text-3xl font-bold">Siamo felici di riverderti
      <span class="text-[#68d4bc]">{{ user != undefined ? user.Nome : "" }}</span></span>
    <div class="w-full flex flex-wrap justify-evenly gap-4 mt-8">
      <FormCard v-for="form of forms" :key="form.ID_Modulo" :id="form.ID_Modulo" :title="form.Titolo"
        :description="form.Descrizione" @delete="(id) => deleteForm(id)" image="https://picsum.photos/600/400" />
    </div>
  </div>
</template>