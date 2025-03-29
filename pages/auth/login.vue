<script lang="ts" setup>
import type { Response } from "~/types";
import { breakpointsTailwind } from "@vueuse/core";

const bp = useBreakpoints(breakpointsTailwind);
const isMobile = bp.isSmaller("sm");

const loading = ref(false);
const toast = useToast();
const router = useRouter();
let token = "";

interface User {
  email: string;
  password: string;
}

const user = ref<User>({
  email: "",
  password: "",
});

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
    const res: Response = await $fetch("https://andrellaveloise.it/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: user.value,
      onResponseError({ response }) {
        throw new Error(response._data.message);
      },
    });

    token = res.data.token;
    const cookieToken = useCookie("token", {
      maxAge: 60 * 60 * 24 * 30, // 30 giorni
      path: "/",
      watch: true,
    });
    cookieToken.value = token;
    router.push("/dashboard");
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 2500,
    });
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <HomeButton class="!absolute bottom-8 right-8" />
  <Toast />
  <div class="flex items-center justify-center h-[100dvh] w-[100dvw] bg-[#68d4bc]">
    <div v-if="!isMobile">
      <Card class="!py-8 !px-4">
        <template #title
          ><span class="font-bold text-4xl">Accesso</span>
          <Divider />
        </template>
        <div class="m-0 flex flex-col gap-2">
          <FloatLabel variant="on">
            <InputText inputId="Email" type="email" v-model="user.email" />
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
      </Card>
    </div>
    <div v-else>
      <div class="font-bold text-4xl flex items-center gap-4">
        <i class="pi pi-sign-in !text-3xl"></i>
        <span>Accesso</span>
      </div>
      <Divider />
      <div class="flex flex-col items-center justify-center gap-8">
        <div class="flex flex-col gap-2">
          <FloatLabel variant="on">
            <InputText inputId="Email" type="email" class="w-[80vw]" v-model="user.email" />
            <label for="Email">Email</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <Password inputId="Password" :fluid="true" v-model="user.password" :feedback="false" />
            <label for="Password">Password</label>
          </FloatLabel>
        </div>
        <Button type="button" label="Accedi" class="w-full" @click="login()" :loading="loading" />
      </div>
    </div>
  </div>
</template>
