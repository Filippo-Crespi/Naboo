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
    <div class="bg-white rounded-xl shadow-2xl p-10 md:min-w-[350px] flex flex-col gap-6 border-b-4 border-[#10b981]">
      <div class="font-bold text-5xl text-center text-[#10b981]">Crea un account</div>
      <Divider />
      <div class="flex flex-col gap-4">
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 place-items-stretch  gap-4">
          <FloatLabel variant="on">
            <InputText inputId="Nome" v-model="user.Nome" class="w-full" />
            <label for="Nome">Nome</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <InputText inputId="Cognome" v-model="user.Cognome" class="w-full" />
            <label for="Cognome">Cognome</label>
          </FloatLabel>
        </div>
        <FloatLabel variant="on">
          <InputText inputId="Email" type="email" class="w-full" v-model="user.Email" />
          <label for="Email">Email</label>
        </FloatLabel>
        <div class="flex flex-col sm:flex-row gap-4">
          <FloatLabel variant="on">
            <InputText inputId="username" v-model="user.Username" class="w-full" />
            <label for="username">Nome utente</label>
          </FloatLabel>
          <FloatLabel variant="on" class="w-full">
            <Password inputId="Password" v-model="user.Password" promptLabel="Scegli una password"
              weakLabel="Troppo semplice" mediumLabel="Normale" strongLabel="Sicura" class="w-full" />
            <label for="Password">Password</label>
          </FloatLabel>
        </div>
      </div>
      <Divider />
      <Button type="button" label="Registrati" icon="pi pi-user-plus" @click="register()" :loading="loading"
        class="w-full" />
    </div>
  </div>
</template>