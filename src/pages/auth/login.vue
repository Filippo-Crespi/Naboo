<script setup>
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import FloatLabel from "primevue/floatlabel";
</script>

<template>
  <Toast />
  <div class="flex items-center justify-center h-screen w-full">
    <div class="flex flex-col gap-2">
      <FloatLabel variant="on">
        <InputText inputId="username" />
        <label for="username">Nome utente</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText inputId="Email" type="email" />
        <label for="Email">Email</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <Password inputId="Password" />
        <label for="Password">Password</label>
      </FloatLabel>
      <Button type="button" label="Entra" icon="pi pi-sign-in" />
    </div>
  </div>
</template>
