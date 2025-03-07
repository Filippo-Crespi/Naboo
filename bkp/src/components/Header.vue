<script setup>
import { goTo } from "../scripts/helper";
import { Button, ButtonGroup } from "primevue";

import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
const dim = useBreakpoints(breakpointsTailwind);
</script>

<template>
  <div
    :class="`fixed ${
      dim.isSmaller('sm') ? 'top-2.5' : 'top-10'
    } left-[50%] transform -translate-x-1/2`">
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
