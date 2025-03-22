<template>
  <div class="flex">
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
    <ScrollPanel class="w-4/5" style="height: calc(100vh - 60px)">
      <DataTable
        v-model:selection="selectedUser"
        selection-mode="single"
        dataKey="id"
        sort-field="id"
        striped-rows
        :value="users"
        paginator
        :rows="20">
        <Column sortable field="id" header="ID" />
        <Column sortable field="nome" header="Nome" />
        <Column sortable field="cognome" header="Cognome" />
        <Column field="mail" header="Email" />
      </DataTable>
    </ScrollPanel>
    <AdminUserDetail v-if="selectedUser" :user="selectedUser" />
  </div>
</template>

<script lang="ts" setup>
const users = ref();
const selectedUser = ref();

users.value = Array.from({ length: 20 }, (_, i) => ({
  id: i + 1,
  nome: `Nome${i + 1}`,
  cognome: `Cognome${i + 1}`,
  mail: `utente${i + 1}@example.com`,
  username: `user${i + 1}`,
  dataRegistrazione: new Date().toLocaleString(),
}));
</script>

<style></style>
