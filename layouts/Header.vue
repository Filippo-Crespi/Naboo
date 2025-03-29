<template>
  <Header :mobile="bp.isSmaller('sm')" />
  <slot />
</template>

<script lang="ts" setup>
import { breakpointsTailwind } from "@vueuse/core";
const bp = useBreakpoints(breakpointsTailwind);
</script>

<style></style>
