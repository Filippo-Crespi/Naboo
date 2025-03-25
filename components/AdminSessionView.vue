<script lang="ts" setup>
const sessions = ref();
sessions.value = Array.from({ length: 20 }, (_, i) => ({
  id: i + 1,
  email: `utente${i + 1}@example.com`,
  token: Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15),
}));
const selectedSession = ref(null);
</script>
<template>
  <div class="flex">
    <ScrollPanel class="w-4/5" style="height: calc(100vh - 60px)">
      <DataTable
        v-model:selection="selectedSession"
        selection-mode="single"
        dataKey="id"
        sort-field="id"
        striped-rows
        :value="sessions"
        paginator
        :rows="20">
        <Column sortable field="id" header="ID" />
        <Column field="email" header="Email" />
        <Column sortable field="token" header="Token" />
      </DataTable>
    </ScrollPanel>
    <AdminSessionDetail v-if="selectedSession" :session="selectedSession" />
  </div>
</template>

<style></style>
