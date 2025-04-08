<script lang="ts" setup>
import type { Error, Response, UserRegister } from "~/types";

const toast = useToast();
const loading = ref(false);
const router = useRouter();

const user = ref(<UserRegister>{
  Nome: "",
  Cognome: "",
  Email: "",
  Password: "",
  Username: "",
});

async function register() {
  for (const key in user.value) {
    if (!user.value[key as keyof UserRegister]) {
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
    const res: Response = await $fetch("https://andrellaveloise.it/register", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: user.value,
      onResponseError({ response }) {
        throw new Error(response._data.message);
      },
    });
    toast.add({
      severity: "success",
      summary: res.message,
      life: 2500,
    });
    await new Promise((resolve) => setTimeout(resolve, 1500));
    router.push("/auth/login");
  } catch (err) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (err as Error).message,
      life: 2500,
    });
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <HomeButton class="!absolute top-4 left-4" />
  <Toast />
  <div class="flex items-center justify-center h-screen w-full bg-[#88b4d4]">
    <Card class="!py-8 !px-4">
      <template #title
        ><span class="font-bold text-4xl">Registrati</span>
        <Divider />
      </template>
      <template #content>
        <div class="m-0 flex flex-col gap-2">
          <div class="flex flex-col sm:flex-row gap-4">
            <FloatLabel variant="on">
              <InputText inputId="Nome" v-model="user.Nome" />
              <label for="Nome">Nome</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <InputText inputId="Cognome" v-model="user.Cognome" />
              <label for="Cognome">Cognome</label>
            </FloatLabel>
          </div>
          <div>
            <FloatLabel variant="on">
              <InputText inputId="Email" type="email" class="w-full" v-model="user.Email" />
              <label for="Email">Email</label>
            </FloatLabel>
          </div>
          <div class="flex flex-col sm:flex-row gap-4">
            <FloatLabel variant="on">
              <InputText inputId="username" v-model="user.Username" />
              <label for="username">Nome utente</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <Password
                inputId="Password"
                v-model="user.Password"
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
