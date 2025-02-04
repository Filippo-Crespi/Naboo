<script setup>
import { InputText, Password, Button, FloatLabel, Divider, Toast, Card } from "primevue";
import { ref } from "vue";

const password = ref("");
const username = ref("");
</script>

<template>
  <Toast />
  <div class="flex items-center justify-center h-screen w-full">
    <Card class="p-4">
      <template #title
        ><span class="font-bold text-4xl">Accesso</span>
        <Divider />
      </template>
      <template #content>
        <div class="m-0 flex flex-col gap-2">
          <FloatLabel variant="on">
            <InputText inputId="Email" type="email" class="w-full" v-model="email" />
            <label for="Email">Email</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <Password inputId="Password" v-model="password" />
            <label for="Password">Password</label>
          </FloatLabel>
        </div>
        <Divider />
        <Button type="button" label="Accedi" icon="pi pi-sign-in" class="w-full" />
      </template>
    </Card>
  </div>
</template>
