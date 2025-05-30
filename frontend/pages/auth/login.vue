<script lang="ts" setup>
import type { Response, User, UserLogin } from "~/types";
import { breakpointsTailwind } from "@vueuse/core";


const bp = useBreakpoints(breakpointsTailwind);
const isMobile = ref(bp.isSmaller("sm"));

const loading = ref(false);
const toast = useToast();
const router = useRouter();
let token = "";

const user = ref<UserLogin>({
  Email: "",
  Password: "",
});

async function login() {
  // Validazione lato client
  if (!user.value.Email || !user.value.Password) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: !user.value.Email ? "Inserisci l'email" : "Inserisci la password",
      life: 2500,
    });
    // Focus automatico sul campo mancante
    setTimeout(() => {
      if (!user.value.Email) document.getElementById('Email')?.focus();
      else document.getElementById('Password')?.focus();
    }, 100);
    return;
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
        // Mostra solo messaggi user-friendly
        throw new Error(response._data?.message || "Errore di autenticazione. Riprova.");
      },
    });
    token = res.data.token;
    const cookieToken = useCookie("token", {
      maxAge: 60 * 60 * 24 * 30, // 30 giorni
      path: "/",
      watch: true,
    });
    cookieToken.value = token;
    try {
      const { data } = await useFetch(
        "https://andrellaveloise.it/users?Token=" +
        token +
        "&stringa=Nome+Cognome+Username+Email+DataReg",
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
          onResponseError({ response }) {
            throw new Error(response._data?.message || "Errore nel recupero dati utente");
          },
        }
      );
      const userData = data.value as Response;
      // memorizza nel cookie
      const cookieUser = useCookie("user", {
        maxAge: 60 * 60 * 24 * 30, // 30 giorni
        path: "/",
        watch: true,
      });
      cookieUser.value = userData.data[0];
    } catch (err) {
      toast.add({
        severity: "warn",
        summary: "Attenzione",
        detail: "Impossibile recuperare i dati utente. Riprova dal menu account.",
        life: 2500,
      });
    }
    router.push("/dashboard");
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message || "Credenziali non valide o server non disponibile.",
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
  <div class="flex items-center justify-center h-[100dvh] w-[100dvw] bg-[#68d4bc]">
    <div v-if="!isMobile">
      <div class="bg-white rounded-xl shadow-2xl p-10 min-w-128 flex flex-col gap-4 border-b-6 border-[#10b981]">
        <div class="font-bold text-5xl text-center text-[#10b981]">Login</div>
        <Divider />
        <div class="flex flex-col gap-4">
          <FloatLabel variant="on">
            <InputText inputId="Email" fluid type="email" v-model="user.Email" />
            <label for="Email">Email</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <Password inputId="Password" fluid v-model="user.Password" :feedback="false" />
            <label for="Password">Password</label>
          </FloatLabel>
        </div>
        <Divider />
        <Button type="button" label="Accedi" icon="pi pi-sign-in" class="w-full" @click="login()" :loading="loading" />
      </div>
    </div>
    <div v-else>
      <div
        class="bg-white rounded-xl shadow-2xl p-6 w-[90vw] max-w-[400px] flex flex-col gap-6 border-b-4 border-[#10b981]">
        <div class="font-bold text-4xl flex items-center gap-4 justify-center text-[#10b981]">
          <i class="pi pi-sign-in !text-3xl "></i>
          <span>Accesso</span>
        </div>
        <Divider />
        <div class="flex flex-col gap-4">
          <FloatLabel variant="on">
            <InputText inputId="Email" type="email" class="w-full" v-model="user.Email" />
            <label for="Email">Email</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <Password inputId="Password" :fluid="true" v-model="user.Password" :feedback="false" />
            <label for="Password">Password</label>
          </FloatLabel>
        </div>
        <Button type="button" label="Accedi" class="w-full" @click="login()" :loading="loading" />
      </div>
    </div>
  </div>
</template>