<script lang="ts" setup>
import { Card, Button, ButtonGroup } from "primevue";
import { onBeforeMount } from "vue";
await new Promise((resolve) => {
  setInterval(() => {
    resolve();
  }, 3000);
});
</script>

<template>
  <div>
    <Card class="w-70">
      <template #header>
        <img src="https://placehold.co/280x180" alt="" />
      </template>
      <template #title>
        <span>Titolo form</span>
      </template>
      <template #subtitle> <span> 00/00/0000 </span> </template>
      <template #content>
        <div class="flex justify-between">
          <ButtonGroup>
            <Button icon="pi pi-pencil" rounded />
            <Button icon="pi pi-chart-line" rounded />
            <Button icon="pi pi-send" rounded />
          </ButtonGroup>
          <Button icon="pi pi-trash" severity="danger" rounded />
        </div>
      </template>
    </Card>
  </div>
</template>

<style></style>
