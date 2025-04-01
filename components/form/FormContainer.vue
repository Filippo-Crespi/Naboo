<script lang="ts" setup>
import type { User, Response } from "~/types";
const user = useCookie("user") as Ref<User>;
const token = useCookie("token") as Ref<string>;
const userForms = ref();
const toast = useToast();

// try {
//   const { data } = useFetch("https://andrellaveloise.it/forms&token=" + token.value, {
//     method: "GET",
//     onResponseError({ response }) {
//       throw new Error(response._data.message);
//     },
//   });
//   userForms.value = (data.value as Response).data;
// } catch (err) {
//   toast.add({
//     severity: "error",
//     summary: "Errore",
//     detail: (err as Error).message,
//     life: 3000,
//   });
// }

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
<template>
  <Toast />
  <div class="!p-8">
    <span class="text-3xl font-bold"
      >Bentornato, {{ user !== undefined ? user.nome : "utente" }}
      {{ user !== undefined ? user.cognome : "utente" }}</span
    >
    <div class="flex gap-4 flex-wrap mt-8">
      <FormCard
        v-for="form of forms"
        :key="form.id"
        :id="form.id"
        :title="form.title"
        :subtitle="form.description" />
    </div>
  </div>
</template>
