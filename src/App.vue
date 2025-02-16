<script setup>
import { RouterView, useRoute } from "vue-router";
import { ref, watch } from "vue";
import Header from "@/components/Header.vue";

const route = useRoute();
const isDashboard = ref(route.path === "/dashboard");

watch(route, (newRoute) => {
  isDashboard.value = newRoute.path === "/dashboard";
});
</script>

<template>
  <Header v-if="!isDashboard" />
  <RouterView />
</template>
