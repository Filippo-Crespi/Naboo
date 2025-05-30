<script setup></script>

<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<style>
.p-toast {
  max-width: 90vw;
}

@media (max-width: 640px) {
  .p-toast {
    width: 90vw !important;
    min-width: 0 !important;
    left: 5vw !important;
    right: 5vw !important;
  }

  .p-toast-message {
    font-size: 1rem;
    padding: 0.75rem 1rem;
  }
}
</style>