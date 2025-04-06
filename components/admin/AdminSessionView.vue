<script lang="ts" setup>
import type { Response } from "~/types";
const toast = useToast();
const sessions = ref();
try {
  const res: Response = await $fetch("https://andrellaveloise.it/sessions?conferma=true", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
    onResponseError({ response }) {
      throw new Error(response._data.message);
    },
  });
  sessions.value = res.data;
} catch (error) {
  toast.add({
    severity: "error",
    summary: "Errore",
    detail: (error as Error).message,
    life: 2500,
  });
}
const selectedSession = ref(null);
const deleteSession = async (token: string) => {
  try {
    const res: Response = await $fetch(`https://andrellaveloise.it/sessions?token=${token}`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (res.success) {
      selectedSession.value = null;
      toast.add({
        severity: "success",
        summary: "Successo",
        detail: "Sessione eliminata",
        life: 2500,
      });
      try {
        const res: Response = await $fetch("https://andrellaveloise.it/sessions?conferma=true", {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
          onResponseError({ response }) {
            throw new Error(response._data.message);
          },
        });
        sessions.value = res.data;
      } catch (error) {
        toast.add({
          severity: "error",
          summary: "Errore",
          detail: (error as Error).message,
          life: 2500,
        });
      }
    }
  } catch (error) {
    console.error(error);
  }
};
</script>
<template>
  <Toast />
  <div class="flex">
    <ScrollPanel class="w-4/5" style="height: calc(100vh - 60px)">
      <DataTable
        v-model:selection="selectedSession"
        selection-mode="single"
        dataKey="ID_Sessione"
        sort-field="Sospeso"
        :sort-order="1"
        striped-rows
        :value="sessions"
        paginator
        :rows="20">
        <Column sortable field="Sospeso" header="Attiva">
          <template #body="slotProps">
            <Badge size="small" :severity="slotProps.data.Sospeso == 1 ? 'danger' : 'success'" />
          </template>
        </Column>
        <Column sortable field="ID_Sessione" header="ID" />
        <Column sortable field="Email" header="Email" />
        <Column field="Token" header="Token" />
      </DataTable>
    </ScrollPanel>
    <AdminSessionDetail
      v-if="selectedSession"
      :session="selectedSession"
      @delete-session="(token) => deleteSession(token)" />
  </div>
</template>

<style></style>
