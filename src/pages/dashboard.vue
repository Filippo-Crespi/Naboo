<script lang="ts" setup>
import Header from "../components/dashboard/header.vue";
import Welcome from "../components/dashboard/welcome.vue";
import Forms from "../components/dashboard/forms/formsBox.vue";
</script>
<template>
  <main>
    <Header />
    <Welcome />
    <Forms />
  </main>
</template>

<style scoped>
main {
  padding: 1rem;
}
</style>
