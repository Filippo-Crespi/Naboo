<script lang="ts" setup>
import type { Response } from "~/types";

const users = ref();
const selectedUser = ref();
const res: Response = await $fetch("https://andrellaveloise.it/users");
users.value = res.data;
</script>
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
        dataKey="ID_Utente"
        sort-field="ID_Utente"
        striped-rows
        :value="users"
        paginator
        :rows="20">
        <Column sortable field="ID_Utente" header="ID" />
        <Column sortable field="nome" header="Nome" />
        <Column sortable field="cognome" header="Cognome" />
        <Column field="email" header="Email" />
      </DataTable>
    </ScrollPanel>
    <AdminUserDetail v-if="selectedUser" :user="selectedUser" />
  </div>
</template>

<style></style>
