<script lang="ts" setup>
import type { Response, User } from "~/types";

const visible = defineModel<boolean>("visible", { required: true });
const token = useCookie("token").value;
const user = ref<User>();
const toast = useToast();
const loading = ref(false);

try {
  const { data } = await useFetch("https://andrellaveloise.it/users?token=" + token, {
    method: "GET",
  });
  user.value = (data.value as Response).data[0] as User;
} catch (error) {
  console.error(error);
}
const updateUser = async () => {
  try {
    loading.value = true;
    const res: Response = await $fetch("https://andrellaveloise.it/users?token=" + token, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: newUser.value,
      onResponseError({ response }) {
        throw new Error(response._data.message);
      },
    });
    if (!res.success) {
      toast.add({
        severity: "error",
        summary: "Errore",
        detail: res.message,
        life: 3000,
      });
    } else {
      toast.add({
        severity: "success",
        summary: "Successo",
        detail: "Utente aggiornato con successo",
        life: 3000,
      });
    }
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

const newUser = ref<User>({
  nome: user.value?.nome!,
  cognome: user.value?.cognome!,
  username: user.value?.username!,
  email: user.value?.email!,
  password: user.value?.password!,
  DataReg: user.value?.DataReg!,
  ID_Utente: user.value?.ID_Utente!,
});
</script>
<template>
  <Toast />
  <Drawer v-model:visible="visible" position="right" header="Left Drawer">
    <template #header>
      <div class="flex items-center gap-2">
        <Avatar
          :image="`https://ui-avatars.com/api/?name=${user?.nome}+${user?.cognome}&rounded=true&bold=true&background=random`"
          shape="circle" />
        <span class="font-bold">@{{ user?.username }}</span>
      </div>
    </template>
    <span class="text-sm text-gray-500 text-center w-full flex justify-center"
      >Registrato il: {{ user?.DataReg }}</span
    >
    <Divider>Modifica i tuoi dati</Divider>
    <div class="flex flex-col gap-4 py-4">
      <FloatLabel variant="on">
        <InputText class="w-full" inputId="Nome" v-model="newUser.nome" />
        <label for="Nome">Nome</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText class="w-full" inputId="Cognome" v-model="newUser.cognome" />
        <label for="Cognome">Cognome</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText class="w-full" inputId="Email" v-model="newUser.email" />
        <label for="Email">Email</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText class="w-full" inputId="Username" v-model="newUser.username" />
        <label for="Username">Username</label>
      </FloatLabel>
      <Button label="Salva" @click="updateUser" :loading="loading" icon="pi pi-save" />
    </div>
    <template #footer>
      <div class="flex items-center gap-2">
        <Button
          label="Logout"
          icon="pi pi-sign-out"
          class="flex-auto"
          severity="danger"
          to="/"
          as="router-link"
          text></Button
        ><Button
          label="Admin"
          icon="pi pi-crown"
          class="flex-auto"
          severity="warn"
          to="/admin"
          as="router-link"
          text></Button>
      </div>
    </template>
  </Drawer>
</template>

<style></style>
