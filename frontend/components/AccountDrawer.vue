<script lang="ts" setup>
import type { User } from '~/types';


const visible = defineModel<boolean>("visible", { required: true });
const session_uuid = useCookie("session_uuid").value;
// @ts-ignore
const user = useCookie<User>("user").value;
const userData = ref({
  session_uuid,
  first_name: user?.first_name || "",
  last_name: user?.last_name || "",
  email: user?.email || "",
  username: user?.username || "",
});
const toast = useToast();
const loading = ref(false);

const updateUser = async () => {
  try {
    loading.value = true;
    await useFetch("http://localhost:8085/api/utenti/utenti.php?", {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: userData.value,
      onResponseError({ response }) {
        throw new Error(response._data.message);
      },
    });
    toast.add({
      severity: "success",
      summary: "Aggiornamento riuscito",
      detail: "Effettuare nuovamente il login per visualizzare le modifiche",
      life: 3000,
    });
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};
</script>
<template>
  <Toast />
  <Drawer v-model:visible="visible" position="right" header="Left Drawer">
    <template #header>
      <div class="flex items-center gap-2">
        <Avatar
          :image="`https://ui-avatars.com/api/?name=${userData.first_name}+${userData.last_name}&rounded=true&bold=true&background=random`"
          shape="circle" />
        <span class="font-bold">@{{ userData.username }}</span>
      </div>
    </template>
    <Divider>Modifica i tuoi dati</Divider>
    <div class="flex flex-col gap-4 py-4">
      <FloatLabel variant="on">
        <InputText class="w-full" inputId="Nome" v-model="userData.first_name" />
        <label for="Nome">Nome</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText class="w-full" inputId="Cognome" v-model="userData.last_name" />
        <label for="Cognome">Cognome</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText class="w-full" inputId="Email" v-model="userData.email" />
        <label for="Email">Email</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText class="w-full" inputId="Username" v-model="userData.username" />
        <label for="Username">Username</label>
      </FloatLabel>
      <Button label="Salva" @click="updateUser" :loading="loading" icon="pi pi-save" />
    </div>
    <template #footer>
      <div class="flex items-center gap-2">
        <Button label="Logout" icon="pi pi-sign-out" class="flex-auto" severity="danger" to="/" as="router-link"
          text></Button><Button label="Admin" icon="pi pi-crown" class="flex-auto" severity="warn" to="/admin"
          as="router-link" text></Button>
      </div>
    </template>
  </Drawer>
</template>