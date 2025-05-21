<script setup lang="ts">
import type { User } from "~/types";

const user = useCookie("user").value as User;

</script>

<template>
  <div class="md:hidden fixed bottom-5 left-1/2 -translate-x-1/2 z-50">
    <Button label="Crea" icon="pi pi-plus" severity="primary" raised @click="$emit('create-form')" />
  </div>
  <div class="flex items-center w-full justify-between bg-white shadow-md p-4 rounded-b-lg">
    <span class="text-3xl font-black flex-1">NABOO</span>
    <div class="mx-auto my-0 hidden md:block">
      <Button label="Crea" icon="pi pi-plus" severity="primary" raised @click="$emit('create-form')" />
      <!-- <Button label="Carica" icon="pi pi-upload" severity="secondary" text disabled /> -->
      <!-- <Button label="Esporta" icon="pi pi-file-pdf" severity="secondary" text disabled /> -->
    </div>
    <div class="flex-1 flex justify-end">
      <Avatar class="hover:cursor-pointer" @click="$emit('open-drawer')" size="large" shape="circle" :image="`https://ui-avatars.com/api/?name=${user.Nome || ''}+${user.Cognome || ''
        }&rounded=true&bold=true&background=random`" />
    </div>
  </div>
</template>

<style></style>