<script setup>
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import { InputText, Button, Password, FloatLabel, Card, Divider } from "primevue";
import $ from "jquery";
import { ref } from "vue";
window.$ = $;
const toast = useToast();

const user = ref({});

const name = ref("");
const surname = ref("");
const email = ref("");
const password = ref("");
const username = ref("");

$.ajax({
  url: "http://localhost/Forms/src/api/services/register.php",
  type: "POST",
  data: {
    name: name.value,
    surname: surname.value,
    email: email.value,
    password: password.value,
    username: username.value,
  },
  success: function (data) {
    // get token
    // start session
  },
  error: function () {
    // unvalid data
  },
});
</script>

<template>
  <Toast />
  <div class="flex items-center justify-center h-screen w-full">
    <Card class="p-4">
      <template #title
        ><span class="font-bold text-4xl">Registrati</span>
        <Divider />
      </template>
      <template #content>
        <div class="m-0 flex flex-col gap-2">
          <div class="flex gap-4">
            <FloatLabel variant="on">
              <InputText inputId="Nome" v-model="name" />
              <label for="Nome">Nome</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <InputText inputId="Cognome" v-model="surname" />
              <label for="Cognome">Cognome</label>
            </FloatLabel>
          </div>
          <div>
            <FloatLabel variant="on">
              <InputText inputId="Email" type="email" class="w-full" v-model="email" />
              <label for="Email">Email</label>
            </FloatLabel>
          </div>
          <div class="flex gap-4">
            <FloatLabel variant="on">
              <InputText inputId="username" v-model="username" />
              <label for="username">Nome utente</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <Password inputId="Password" v-model="password" />
              <label for="Password">Password</label>
            </FloatLabel>
          </div>
          <Divider />
          <Button type="button" label="Registrati" icon="pi pi-user-plus" />
        </div>
      </template>
    </Card>
  </div>
</template>

<style scoped>
span {
  color: #333;
}
</style>
