<script lang="ts" setup>
import type { User } from "~/types";

// @ts-ignore
const user = useCookie("user").value as User;

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
      >Siamo felici di riverderti
      <span class="text-[#68d4bc]">{{ user != undefined ? user.Nome : "" }}</span></span
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
