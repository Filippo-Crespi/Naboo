<script lang="ts" setup>
const visible = ref(true);
</script>
<template>
  <div>
    <Dialog modal v-model:visible="visible">
      <template #header>
        <span class="flex items-center gap-2 text-3xl font-medium">
          <Icon name="material-symbols:account-circle" /> Utente #n
        </span>
      </template>
      <template #default>
        <div class="flex flex-col gap-2">
          <span class="text-lg font-medium">Informazioni</span>
          <div class="flex justify-between">
            <div class="flex flex-col gap-2">
              <span class="text-sm font-medium">Nome</span>
              <span class="text-sm">Mario</span>
            </div>
            <div class="flex flex-col gap-2">
              <span class="text-sm font-medium">Cognome</span>
              <span class="text-sm">Rossi</span>
            </div>
          </div>
        </div>
      </template>
    </Dialog>
  </div>
</template>

<style></style>
