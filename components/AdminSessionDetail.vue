<script lang="ts" setup>
const props = defineProps({
  session: {
    type: Object,
    required: true,
  },
});
</script>
<template>
  <div class="w-1/5 flex flex-col items-center gap-4 py-10 px-2 border-l-1 border-gray-200">
    <div class="flex flex-col items-center gap-2">
      <span class="text-gray-400">{{ session.id }}</span>
    </div>
    <div class="flex flex-col items-center">
      <span class="text-xl font-bold">{{ session.nome }} {{ session.cognome }}</span>
      <span class="text-gray-400 font-thin">@{{ session.username }}</span>
    </div>
    <div class="flex flex-col items-center gap-2">
      <Button
        @click="$emit('deleteSession', session.id)"
        icon="pi pi-trash"
        class="w-full"
        size="small"
        label="Sospendi"
        severity="warn"
        rounded />
    </div>
  </div>
</template>

<style></style>
