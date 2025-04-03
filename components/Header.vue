<template>
  <div class="h-1/9">
    <div v-if="mobile">
      <div class="fixed top-5 left-1/2 -translate-x-1/2 rounded-2xl bg-[#ecfbf7] px-8 py-2">
        <span class="text-3xl font-extrabold">NABOO</span>
      </div>
    </div>
    <div v-else>
      <div
        class="fixed top-5 left-5 flex items-center justify-center gap-4 px-8 py-2 bg-[#ecfbf7] rounded-[4rem] shadow-2xl">
        <Logo />
        <ButtonGroup>
          <Button icon="pi pi-home" label="Home" as="router-link" to="/" rounded />
          <Button icon="pi pi-github" label="Github" as="a" to="https://github.com" rounded />
        </ButtonGroup>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  mobile: {
    type: Boolean,
    default: false,
  },
});
</script>

<style></style>
