<script setup lang="ts">
import type { User } from '~/types';


const user = useCookie<User>("user").value;

</script>

<template>
  <div class="md:hidden fixed bottom-5 left-1/2 -translate-x-1/2 z-50">
    <Button label="Crea" icon="pi pi-plus" severity="primary" raised @click="$emit('create-form')" />
  </div>
  <div class="flex items-center w-full justify-between bg-white shadow-md p-4 rounded-b-lg">
    <span class="text-3xl font-black flex-1">NABOO</span>
    <div class="mx-auto my-0 hidden md:block">
      <Button severity="primary" raised @click="$emit('create-form')">
        <Icon name="solar:add-square-bold-duotone" class="text-2xl" />
        <span class="font-bold">Crea</span>
      </Button>
      <!-- <Button label="Carica" icon="pi pi-upload" severity="secondary" text disabled /> -->
      <!-- <Button label="Esporta" icon="pi pi-file-pdf" severity="secondary" text disabled /> -->
    </div>
    <div class="flex-1 flex justify-end">
      <Avatar class="hover:cursor-pointer" @click="$emit('open-drawer')" size="large" shape="circle" :image="`https://ui-avatars.com/api/?name=${user.first_name || ''}+${user.last_name || ''
        }&rounded=true&bold=true&background=random`" />
    </div>
  </div>
</template>

<style></style>