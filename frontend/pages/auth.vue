<script lang="ts" setup>
import { breakpointsTailwind } from "@vueuse/core";


const bp = useBreakpoints(breakpointsTailwind);
const isMobile = ref(bp.isSmaller("sm"));

const loading = ref(false);
const toast = useToast();
const router = useRouter();
let token = "";

const mode = ref<'login' | 'register'>('login');

const userLogin = ref({
  email: "",
  password: "",
});

const userRegister = ref({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  username: "",
});

interface LoginResponse {
  success: boolean;
  message: string;
  data: any;
}

async function login() {
  try {
    loading.value = true;
    const res: LoginResponse = await $fetch("http://localhost:8085/api/autenticazione/login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: userLogin.value,
    });
    if (!res.success) throw new Error(res.message);
    toast.add({
      severity: "success",
      summary: res.message,
      life: 2500,
    });
    if (res.data) {
      // Salva token nei cookie se presente
      const cookieSession = useCookie("session_uuid", {
        maxAge: 60 * 60 * 24 * 30, // 30 giorni
        path: "/",
        watch: true,
      });
      cookieSession.value = res.data.session_uuid;
      // Salva utente nei cookie
      const cookieUser = useCookie("user", {
        maxAge: 60 * 60 * 24 * 30,
        path: "/",
        watch: true
      });
      cookieUser.value = res.data.user
    }
    await new Promise((resolve) => setTimeout(resolve, 1000));
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

async function register() {
  try {
    loading.value = true;
    const res: any = await $fetch("http://localhost:8085/api/autenticazione/registrazione.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: userRegister.value,
    });
    if (!res.success) throw new Error(res.message);
    toast.add({
      severity: "success",
      summary: res.message,
      life: 2500,
    });
    await new Promise((resolve) => setTimeout(resolve, 1500));
    mode.value = 'login';
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
  <Toast />
  <header
    class="absolute w-full flex items-center justify-between p-4 bg-[#10b981] border-b-2 text-white mb-4 rounded-b-xl shadow-md">
    <div class="flex items-center gap-2">
      <span class="text-3xl font-black ">NABOO</span>
    </div>
  </header>
  <div class="flex items-center justify-center min-h-screen w-full bg-[#68d4bc]">
    <div class="w-full flex justify-center">
      <div
        class="flex flex-col gap-4 w-full h-screen min-h-0 max-h-none p-4 bg-white mx-0 sm:mx-auto sm:max-w-[620px] sm:min-h-[400px] sm:max-h-[55vh] sm:justify-center sm:rounded-xl sm:shadow-2xl sm:border-b-6 sm:border-[#10b981] sm:px-8 sm:py-4 justify-center items-center">
        <div class="w-full max-w-[500px]">
          <div class="flex justify-between items-center gap-2">
            <div class="font-bold text-3xl sm:text-4xl text-center text-[#10b981]">
              {{ mode === 'login' ? 'Login' : 'Registrazione' }}
            </div>
            <Button :label="mode === 'login' ? 'Registrati' : 'Accedi'" class="ml-0 sm:ml-4 mt-2 sm:mt-0" size="small"
              @click="mode = mode === 'login' ? 'register' : 'login'" outlined />
          </div>
          <Divider />
          <Transition name="fade-slide" mode="out-in">
            <div v-if="mode === 'login'" key="login" class="flex flex-col gap-4">
              <FloatLabel variant="on">
                <InputText inputId="Email" fluid type="email" v-model="userLogin.email" />
                <label for="Email">Email</label>
              </FloatLabel>
              <FloatLabel variant="on">
                <Password inputId="Password" fluid v-model="userLogin.password" :feedback="false" />
                <label for="Password">Password</label>
              </FloatLabel>
            </div>
            <div v-else key="register" class="flex flex-col gap-4">
              <div class="w-full grid grid-cols-1 sm:grid-cols-2 place-items-stretch gap-4">
                <FloatLabel variant="on">
                  <InputText inputId="Nome" v-model="userRegister.first_name" class="w-full" />
                  <label for="Nome">Nome</label>
                </FloatLabel>
                <FloatLabel variant="on">
                  <InputText inputId="Cognome" v-model="userRegister.last_name" class="w-full" />
                  <label for="Cognome">Cognome</label>
                </FloatLabel>
              </div>
              <FloatLabel variant="on">
                <InputText inputId="Email" type="email" class="w-full" v-model="userRegister.email" />
                <label for="Email">Email</label>
              </FloatLabel>
              <!-- Username e Password: griglia solo su sm, flex su mobile, forzata larghezza piena -->
              <div class="w-full min-w-0 flex flex-col gap-4 sm:grid sm:grid-cols-2 sm:place-items-stretch sm:gap-4">
                <FloatLabel variant="on" class="w-full flex-1">
                  <InputText inputId="username" v-model="userRegister.username" class="w-full" />
                  <label for="username">Nome utente</label>
                </FloatLabel>
                <FloatLabel variant="on" class="flex-1">
                  <Password inputId="Password" v-model="userRegister.password" promptLabel="Scegli una password"
                    weakLabel="Troppo semplice" mediumLabel="Normale" strongLabel="Sicura" class="w-full" />
                  <label for="Password">Password</label>
                </FloatLabel>
              </div>
            </div>
          </Transition>
          <Divider />
          <Button v-if="mode === 'login'" type="button" label="Accedi" icon="pi pi-sign-in" class="w-full"
            @click="login()" :loading="loading" />
          <Button v-else type="button" label="Registrati" icon="pi pi-user-plus" class="w-full" @click="register()"
            :loading="loading" />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(24px);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-24px);
}

:deep(.p-password-input),
:deep(.p-password .p-inputtext) {
  width: 100% !important;
  min-width: 0 !important;
  box-sizing: border-box;
}
</style>