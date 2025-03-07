<script lang="ts" setup>
import { Card, Skeleton } from "primevue";
</script>

<template>
  <div>
    <Card class="w-70">
      <template #header>
        <Skeleton class="!h-45" />
      </template>
      <template #title>
        <Skeleton>Titolo form</Skeleton>
      </template>
      <template #subtitle> <Skeleton class="!w-1/2" /> </template>
      <template #content>
        <div class="flex justify-between gap-4">
          <div class="flex justify-center gap-2">
            <Skeleton width="3rem" height="2rem"></Skeleton>
            <Skeleton width="3rem" height="2rem"></Skeleton>
            <Skeleton width="3rem" height="2rem"></Skeleton>
          </div>
          <Skeleton width="3rem" height="2rem"></Skeleton>
        </div>
      </template>
    </Card>
  </div>
</template>

<style></style>
