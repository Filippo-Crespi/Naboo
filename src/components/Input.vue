<script setup>
import { ref } from 'vue';

const model = ref('');

const props = defineProps({
  placeholder: {
    type: String,
    default: 'placeholder',
  },
  type: {
    type: String,
    default: 'text',
    required: true,
  },
  name: {
    type: String,
    default: '',
  },
  id: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <input
    :class="
      type == 'text'
        ? 'w-full h-11 rounded-lg border-2 border-violet-300 bg-violet-100 bg py-[1.125rem] outline-0 pl-4'
        : ''
    "
    :type="type"
    v-model="model"
    :placeholder="placeholder"
    :name="name"
    :id="id" />
</template>
