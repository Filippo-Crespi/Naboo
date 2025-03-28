<script lang="ts" setup>
import { AdminUserView, AdminSessionView, AdminFormsView, AdminReportsView } from "#components";
const dic = {
  AdminUserView: AdminUserView,
  AdminSessionView: AdminSessionView,
  AdminFormsView: AdminFormsView,
  AdminReportsView: AdminReportsView,
};
const tab = shallowRef(AdminUserView);
const update = (name: string) => {
  tab.value = dic[name];
};
// controlli su validità token admin
</script>
<template>
  <div class="flex">
    <AdminNav
      @update-tab="
        (name) => {
          update(name);
        }
      " />
    <div class="w-full">
      <AdminHeader />
      <div>
        <component :is="tab"></component>
      </div>
    </div>
  </div>
</template>
