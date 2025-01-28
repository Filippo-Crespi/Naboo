<script setup>
import { Icon } from '@iconify/vue';

const props = defineProps({
  text: {
    type: String,
    default: '',
  },
  dim: {
    type: String,
    default: 'r',
  },
  icon: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <div>
    <button
      :class="[
        `bg-violet-700 border-none text-white cursor-pointer font-semibold ${
          dim == 's' ? 'h-8 rounded-md' : dim == 'r' ? 'h-11 rounded-lg' : 'h-13 rounded-lg'
        } ${
          icon && text
            ? dim == 's'
              ? 'pl-3 pr-4 gap-1'
              : dim == 'r'
              ? 'pl-3 pr-4 gap-2'
              : 'pl-7 pr-8 gap-2'
            : icon
            ? dim == 's'
              ? 'py-2 pl-2 pr-2'
              : dim == 'r'
              ? 'py-3 pl-3 pr-3'
              : 'py-3.5 pl-3.5 pr-3.5'
            : 'px-4'
        }`,
        icon ? 'flex flex-row items-center' : '',
      ]"
      type="button">
      <Icon
        :width="dim == 's' ? '1rem' : dim == 'r' ? '1.25rem' : '1.5rem'"
        v-if="icon"
        :icon="icon" />
      <span v-if="text">{{ text }}</span>
    </button>
  </div>
</template>
