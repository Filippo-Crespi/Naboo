<script setup>
import { goTo } from "../scripts/helper";
import { Button, ButtonGroup } from "primevue";
</script>

<template>
  <div class="absolute top-10 left-[50%] transform -translate-x-1/2">
    <ButtonGroup>
      <Button icon="pi pi-home" label="Home" severity="secondary" @click="goTo('/')" rounded />
      <Button icon="pi pi-github" label="Github" severity="secondary" />
      <Button
        icon="pi pi-question-circle"
        label="Faq"
        severity="secondary"
        @click="goTo('/faq')"
        rounded />
    </ButtonGroup>
  </div>
</template>
