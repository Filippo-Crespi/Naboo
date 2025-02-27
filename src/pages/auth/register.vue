<script setup>
import { InputText, Button, Password, FloatLabel, Card, Divider, Toast, useToast } from "primevue";
import { ref } from "vue";
import AuthAPI from "../../services/AuthAPI";
import { goTo } from "@/scripts/helper";

const toast = useToast();
const loading = ref(false);

const user = ref({
  name: "",
  surname: "",
  email: "",
  password: "",
  username: "",
});

async function register() {
  for (const key in user.value) {
    if (!user.value[key]) {
      toast.add({
        severity: "error",
        summary: "Errore",
        detail: "Compilare tutti i campi",
        life: 2500,
      });
      return;
    }
  }

  try {
    loading.value = true;
    const data = {
      name: user.value.name,
      surname: user.value.surname,
      email: user.value.email,
      username: user.value.username,
      password: user.value.password,
    };
    const res = await AuthAPI.postRegister(data);
    if (!res.data.success) {
      toast.add({
        severity: "error",
        summary: "Errore",
        detail: res.data.msg,
        life: 2500,
      });
    } else {
      goTo(`/login`);
    }
  } catch (err) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: "Errore del server, riprovare",
      life: 2500,
    });
  } finally {
    loading.value = false;
    // goTo(`/auth/login`);
  }
}
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
          <div class="flex flex-col sm:flex-row gap-4">
            <FloatLabel variant="on">
              <InputText inputId="Nome" v-model="user.name" />
              <label for="Nome">Nome</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <InputText inputId="Cognome" v-model="user.surname" />
              <label for="Cognome">Cognome</label>
            </FloatLabel>
          </div>
          <div>
            <FloatLabel variant="on">
              <InputText inputId="Email" type="email" class="w-full" v-model="user.email" />
              <label for="Email">Email</label>
            </FloatLabel>
          </div>
          <div class="flex flex-col sm:flex-row gap-4">
            <FloatLabel variant="on">
              <InputText inputId="username" v-model="user.username" />
              <label for="username">Nome utente</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <Password
                inputId="Password"
                v-model="user.password"
                promptLabel="Scegli una password"
                weakLabel="Troppo semplice"
                mediumLabel="Normale"
                strongLabel="Sicura" />
              <label for="Password">Password</label>
            </FloatLabel>
          </div>
          <Divider />
          <Button
            type="button"
            label="Registrati"
            icon="pi pi-user-plus"
            @click="register()"
            :loading="loading" />
        </div>
      </template>
    </Card>
  </div>
</template>
