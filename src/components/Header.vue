<script setup>
import AppearIcon from './AppearIcon.vue';
</script>

<template>
  <div
    class="absolute top-6 left-[50%] transform -translate-x-1/2 flex justify-center gap-4 p-4 rounded-4xl bg-amber-300 backdrop-blur-2xl">
    <div class="appear-icon">
      <AppearIcon text="Home" width="32px" icon="material-symbols:home-rounded" />
    </div>
    <div class="appear-icon">
      <AppearIcon text="About" width="32px" icon="ix:about-filled" />
    </div>
    <div class="appear-icon">
      <AppearIcon text="Github" width="32px" icon="mdi:github" />
    </div>
  </div>
</template>

<style scoped>
.appear-icon:hover {
  transform: scale(1.1);
}

.appear-icon {
  transition: transform 0.5s;
}
</style>
