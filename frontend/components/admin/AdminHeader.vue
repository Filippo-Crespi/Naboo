<script lang="ts" setup>
import type { User } from "~/types";

const user = useCookie("user") as Ref<User>;
</script>

<template>
  <div class="w-full h-15 p-4 flex justify-end border-b-1 border-gray-200">
    <div class="flex items-center gap-2">
      <span class="text-gray-500"
        >{{ user != undefined ? user.Nome : "" }} {{ user != undefined ? user.Cognome : "" }}</span
      >
      <Avatar
        shape="circle"
        :image="`https://ui-avatars.com/api/?name=${user?.Nome}+${user?.Cognome}&rounded=true&bold=true&background=random`" />
    </div>
  </div>
</template>

<style></style>
