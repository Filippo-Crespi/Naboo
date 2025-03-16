<script lang="ts" setup>
const toast = useToast();
const loading = ref(false);
const router = useRouter();

interface User {
  name: string;
  surname: string;
  email: string;
  password: string;
  username: string;
}

const user = ref(<User>{
  name: "",
  surname: "",
  email: "",
  password: "",
  username: "",
});

async function register() {
  for (const key in user.value) {
    if (!user.value[key as keyof User]) {
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
    const res: Response = await $fetch(
      "https://andrellaveloise.it/api/autenticazione/register.php",
      {
        method: "POST",
        body: JSON.stringify(user.value),
      }
    );
    if (res.ok) {
      // reindirizza alla dashboard
      router.push("/auth/login");
    }
  } catch (err) {
    toast.add({
      severity: "error",
      summary: (err as Error).name,
      detail: (err as Error).message,
      life: 2500,
    });
  } finally {
    loading.value = false;
  }
}
</script>

<template>
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
