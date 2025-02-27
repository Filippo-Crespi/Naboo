<script setup>
import { Icon } from '@iconify/vue';
import { ref } from 'vue';

const show = ref(false);

const props = defineProps({
  icon: {
    type: String,
    required: true,
  },
  text: {
    type: String,
    required: true,
  },
  width: {
    type: String,
    default: '32px',
  },
});
</script>

<template>
  <div
    class="cursor-pointer flex items-center gap-2"
    @mouseover="show = true"
    @mouseleave="show = false">
    <Icon :width="width" :icon="icon" />
    <span v-show="show">{{ text }}</span>
  </div>
</template>

<style scoped>
span {
  animation: appear 0.5s;
}

@keyframes appear {
  0% {
    opacity: 0;
    transform: translateX(-10px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>
