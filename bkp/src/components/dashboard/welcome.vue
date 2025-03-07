<script lang="ts" setup>
import AuthAPI from "../../services/AuthAPI";
import { getToken, goTo } from "../../scripts/helper";
import { ref, onMounted } from "vue";
import { Divider } from "primevue";

const name = ref("");

onMounted(async () => {
  try {
    const user = await AuthAPI.getUser(getToken());
    name.value = user.data.name;
  } catch (err) {
    goTo("/login");
  }
});
</script>

<template>
  <div>
    <span class="font-bold text-5xl"
      >Bentornato, <span>{{ name }}!</span></span
    >
  </div>
  <Divider />
</template>

<style scoped>
div {
  margin-top: 2rem;
}
</style>
