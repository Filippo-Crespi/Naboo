<script lang="ts" setup>
import { AdminUserView, AdminSessionView } from "#components";

definePageMeta({
  middleware: ["auth"],
});

// Mapping dei componenti
const dic: Record<string, any> = {
  AdminUserView,
  AdminSessionView,
  // AdminFormsView,
  // AdminReportsView,
};

const tab = ref(AdminUserView);

const updateTab = (name: string) => {
  tab.value = dic[name];
};
</script>

<template>
  <div>
    <div class="md:hidden min-h-screen flex flex-col items-center justify-center bg-white">
      <div class="text-2xl font-bold text-[#10b981] mb-2">Accesso da desktop richiesto</div>
      <div class="text-gray-500 text-center">Per utilizzare l'area amministratore accedi da un computer desktop o
        allarga la finestra.</div>
    </div>
    <div class="hidden md:flex">
      <AdminNav @update-tab="updateTab" class="hidden md:block" />
      <div class="w-full">
        <AdminHeader />
        <div>
          <component :is="tab" />
        </div>
      </div>
    </div>
  </div>
</template>