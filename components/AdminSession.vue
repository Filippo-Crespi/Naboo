<script lang="ts" setup></script>
<template>
  <Dialog modal>
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
  <div
    class="h-[85vh] w-1/3 flex flex-col !p-4 !border-2 !border-gray-300 rounded-lg overflow-y-scroll">
    <span class="flex items-center gap-2 text-3xl font-medium"
      ><Icon name="material-symbols:account-circle" /> Utenti registrati
    </span>
    <Divider>Le eliminazioni sono definitive</Divider>
    <div>
      <AdminSessionUser v-for="index in 20" :key="index" />
    </div>
  </div>
</template>

<style></style>
