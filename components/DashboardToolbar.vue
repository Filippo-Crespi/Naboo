<template>
  <div>
    <Toolbar>
      <template #start>
        <span class="text-2xl font-bold">Naboo</span>
      </template>
      <template #center>
        <Button icon="pi pi-plus" class="mr-2" severity="secondary" text />
        <Button icon="pi pi-upload" severity="secondary" text />
        <Button icon="pi pi-file-pdf" class="mr-2" severity="secondary" text />
      </template>
      <template #end><Avatar icon="pi pi-user" shape="circle" /> </template>
    </Toolbar>
  </div>
</template>

<script lang="ts" setup></script>

<style></style>
