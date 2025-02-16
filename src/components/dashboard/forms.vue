<script lang="ts" setup>
import { Suspense } from "vue";
import Formdata from "../../components/dashboard/formdata.vue";
</script>

<template>
  <div>
    <Suspense>
      <Formdata />
      <template #fallback>loading</template>
    </Suspense>
  </div>
</template>

<style></style>
