<script setup lang="ts">
import type { User } from "~/types";

// @ts-ignore
const user = useCookie("user").value as User;
</script>

<template>
  <div>
    <Toolbar>
      <template #start>
        <img src="/imgs/logo.png" class="h-12" alt="" />
      </template>
      <template #center>
        <Button
          label="Crea"
          icon="pi pi-plus"
          class="mr-2"
          severity="primary"
          raised
          @click="$emit('create-form')" />
        <Button label="Carica" icon="pi pi-upload" severity="secondary" text disabled />
        <Button label="Esporta" icon="pi pi-file-pdf" severity="secondary" text disabled />
      </template>
      <template #end>
        <Avatar
          class="hover:cursor-pointer"
          @click="$emit('open-drawer')"
          size="large"
          shape="circle"
          :image="`https://ui-avatars.com/api/?name=${user.Nome || ''}+${
            user.Cognome || ''
          }&rounded=true&bold=true&background=random`" />
      </template>
    </Toolbar>
  </div>
</template>

<style></style>
