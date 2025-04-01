<script lang="ts" setup>
import type { User } from "~/types";
const user = useCookie("user") as Ref<User>;
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
  <div>
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

<style></style>
