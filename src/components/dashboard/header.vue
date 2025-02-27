<script lang="ts" setup>
import { Toolbar, Button, IconField, InputText, SplitButton, InputIcon, Avatar } from "primevue";
</script>
<template>
  <Toolbar>
    <template #start>
      <Button icon="pi pi-plus" class="mr-2" severity="secondary" text />
      <Button icon="pi pi-print" class="mr-2" severity="secondary" text />
      <Button icon="pi pi-download" severity="secondary" text />
    </template>

    <template #center>
      <IconField>
        <InputIcon>
          <i class="pi pi-search" />
        </InputIcon>
        <InputText placeholder="Cerca modulo" />
      </IconField>
    </template>

    <template #end>
      <Avatar image="https://avatar.iran.liara.run/public" shape="circle" size="large" />
      <!-- <Button /> -->
    </template>
  </Toolbar>
</template>

<style></style>
