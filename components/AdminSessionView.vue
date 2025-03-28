<script lang="ts" setup>
import type { Response } from "~/types";

const sessions = ref();
const res: Response = await $fetch("https://andrellaveloise.it/sessions");
sessions.value = res.data;
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
      sessions.value = sessions.value.filter((session: any) => session.token !== token);
    }
  } catch (error) {
    console.error(error);
  }
};
</script>
<template>
  <div class="flex">
    <ScrollPanel class="w-4/5" style="height: calc(100vh - 60px)">
      <DataTable
        v-model:selection="selectedSession"
        selection-mode="single"
        dataKey="ID_Sessione"
        sort-field="ID_Sessione"
        striped-rows
        :value="sessions"
        paginator
        :rows="20">
        <Column sortable field="ID_Sessione" header="ID" />
        <Column field="email" header="Email" />
        <Column sortable field="Token" header="Token" />
      </DataTable>
    </ScrollPanel>
    <AdminSessionDetail
      v-if="selectedSession"
      :session="selectedSession"
      @delete-session="(token) => deleteSession(token)" />
  </div>
</template>

<style></style>
