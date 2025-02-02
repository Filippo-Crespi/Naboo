<script setup>
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button";
import FloatLabel from "primevue/floatlabel";
import $ from "jquery";
window.$ = $;
const toast = useToast();
</script>

<template>
  <Toast />
  <div class="flex items-center justify-center h-screen w-full">
    <div class="flex flex-col gap-2">
      <FloatLabel variant="on">
        <InputText inputId="Nome" />
        <label for="Nome">Nome</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText inputId="Cognome" />
        <label for="Cognome">Cognome</label>
      </FloatLabel>
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
      <Button type="button" label="Registrati" icon="pi pi-user-plus" />
    </div>
  </div>
</template>
