<script lang="ts" setup>
import type { Response, User } from "~/types";

const users = ref();
const selectedUser = ref();
const toast = useToast();
const dbUsers: Response = await $fetch("https://andrellaveloise.it/users");
users.value = dbUsers.data;

const deleteUser = async (id: string) => {
  try {
    let res: Response = await $fetch(`https://andrellaveloise.it/users?id=${id}`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      onResponseError({ response }) {
        throw new Error(response._data.message);
      },
    });
    toast.add({
      severity: "success",
      summary: "Successo",
      detail: res.message,
      life: 3000,
    });
    res = await $fetch("https://andrellaveloise.it/users");
    users.value = res.data;
  } catch (err) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (err as Error).message,
      life: 3000,
    });
  }
};
</script>
<template>
  <Toast />
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
      <DataTable v-model:selection="selectedUser" selection-mode="single" dataKey="ID_Utente" sort-field="ID_Utente"
        striped-rows :value="users" paginator :rows="20">
        <Column sortable field="ID_Utente" header="ID" />
        <Column sortable field="Nome" header="Nome" />
        <Column sortable field="Cognome" header="Cognome" />
        <Column sortable field="Email" header="Email" />
      </DataTable>
    </ScrollPanel>
    <AdminUserDetail v-if="selectedUser" :user="selectedUser" @delete-user="
      (id) => {
        deleteUser(id);
      }
    " />
  </div>
</template>

<style></style>