<script lang="ts" setup>
import { ref } from "vue";
import { AdminUserView, AdminSessionView, AdminFormsView, AdminReportsView } from "#components";

definePageMeta({
  middleware: ["auth"],
});

// Mapping dei componenti
const dic = {
  AdminUserView,
  AdminSessionView,
  AdminFormsView,
  AdminReportsView,
};

const tab = ref(AdminUserView);

const updateTab = (name: string) => {
  tab.value = dic[name];
};
</script>

<template>
  <div class="flex">
    <AdminNav @update-tab="updateTab" />
    <div class="w-full">
      <AdminHeader />
      <div>
        <component :is="tab" />
      </div>
    </div>
  </div>
</template>
