<script lang="ts" setup>
const tab = ref("AdminUserView");
const emit = defineEmits(["update-tab"]);

const setTab = (name: string) => {
  tab.value = name;
  emit("update-tab", name);
};

const items = ref([
  { component: "AdminUserView", label: "Utenti", icon: "gridicons:multiple-users" },
  { component: "AdminSessionView", label: "Sessioni", icon: "radix-icons:tokens" },
]);
</script>
<template>
  <nav class="min-w-60 h-screen py-4 px-2 flex flex-col border-r-1 border-gray-200">
    <div class="flex flex-col gap-12">
      <Logo />
      <div>
        <Tabs :value="tab">
          <TabList>
            <Tab v-for="tab in items" :key="tab.label" :value="tab.component" @click="setTab(tab.component)">
              <div class="flex items-center gap-2">
                <Icon :name="tab.icon" size="1.5rem" />
                <span>{{ tab.label }}</span>
              </div>
            </Tab>
          </TabList>
        </Tabs>
      </div>
    </div>
    <Button to="/dashboard" as="router-link" label="Torna alla dashboard" fluid icon="pi pi-sign-out" severity="warn"
      class="!w-full mt-8" />
  </nav>
</template>

<style></style>