<script setup>
import { InputText, Password, Button, FloatLabel, Divider, Toast, Card, useToast } from "primevue";
import { ref } from "vue";
import AuthAPI from "../../services/AuthAPI";

const loading = ref(false);
const toast = useToast();

async function login() {
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
    const res = await AuthAPI.postLogin({ user: user.value });
    if (!res.data.success) {
      toast.add({
        severity: "error",
        summary: "Errore",
        detail: "Utente non registrato",
        life: 2500,
      });
    } else {
      const token = res.data.token;
      document.cookie = `token=${token}; path=/`;
      goTo(`/dashboard`);
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
  }
}

const user = ref({
  email: "",
  password: "",
});
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
            <InputText inputId="Email" type="email" class="w-full" v-model="user.email" />
            <label for="Email">Email</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <Password inputId="Password" v-model="user.password" :feedback="false" />
            <label for="Password">Password</label>
          </FloatLabel>
        </div>
        <Divider />
        <Button
          type="button"
          label="Accedi"
          icon="pi pi-sign-in"
          class="w-full"
          @click="login()"
          :loading="loading" />
      </template>
    </Card>
  </div>
</template>
