<script lang="ts" setup>
const loading = ref(false);
const toast = useToast();
const router = useRouter();

async function login() {
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
    const res = await $fetch("https://andrellaveloise.it/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: user.value,
    });
    console.log(res);
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

interface User {
  email: string;
  password: string;
}

const user = ref<User>({
  email: "",
  password: "",
});
</script>

<template>
  <Toast />
  <div class="flex items-center justify-center h-screen w-full bg-[#68d4bc]">
    <Card class="!py-8 !px-4">
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
