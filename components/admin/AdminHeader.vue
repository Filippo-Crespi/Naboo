<template>
  <div class="w-full h-15 p-4 flex justify-end border-b-1 border-gray-200">
    <div class="flex items-center gap-2">
      <span class="text-gray-500">{{ user.nome }} {{ user.cognome }}</span>
      <Avatar shape="circle" image="https://placehold.co/32" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import type { User } from "~/types";

const user = useCookie("user") as Ref<User>;
</script>

<style></style>
