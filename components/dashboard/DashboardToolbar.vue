<script lang="ts">
import { type User } from "~/types";
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
        <Button label="Carica" icon="pi pi-upload" severity="secondary" text />
        <Button label="Esporta" icon="pi pi-file-pdf" class="mr-2" severity="secondary" text />
      </template>
      <template #end>
        <div class="flex items-center gap-2">
          <span>Nome Cognome</span>
          <Avatar @click="$emit('open-drawer')" icon="pi pi-user" shape="circle" />
        </div>
      </template>
    </Toolbar>
  </div>
</template>

<style></style>
