<script setup>
import { Button, FloatLabel, InputText, Toast, Card, Divider, useToast } from "primevue";
import { ref } from "vue";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import { goTo } from "@/scripts/helper";

import FormAPI from "../services/FormAPI";

const dim = useBreakpoints(breakpointsTailwind);
const toast = useToast();
const code = ref("");
const loading = ref(false);

function codeRegex(code) {
  return /^[a-zA-Z0-9]{6}$/.test(code);
}

async function codeValid() {
  if (!codeRegex(code.value)) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: "Formato del codice non valido",
      life: 2500,
    });
    return;
  }
  loading.value = true;
  try {
    const res = await FormAPI.getForm({ code: code.value });
    if (!res.data.success) {
      toast.add({
        severity: "error",
        summary: "Errore",
        detail: "Modulo non trovato",
        life: 2500,
      });
    } else {
      goTo(`/modules/${res.data.code}`);
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
</script>

<template>
  <Toast />
  <div
    class="flex sm:flex-row flex-col-reverse h-screen bg-[url('/imgs/home/background.png')] bg-cover bg-center">
    <div class="w-full h-1/2 sm:h-full bg-blue-200 sm:flex sm:items-center sm:justify-center">
      <Card
        class="flex items-center justify-center p-4 sm:p-12 h-full sm:h-auto !rounded-none sm:!rounded-2xl">
        <template #title>
          <span class="text-center font-black text-4xl uppercase">Crea un modulo</span>
        </template>
        <template #content>
          <Divider align="center" type="solid">
            <p>Registrazione necessaria</p>
          </Divider>
          <div class="flex flex-col gap-2 w-full">
            <Button
              icon="pi pi-user-plus"
              label="Registrati"
              as="router-link"
              to="/auth/register"
              class="w-full" />
            <Button
              class="w-full"
              variant="outlined"
              as="router-link"
              to="/auth/login"
              icon="pi pi-sign-in"
              label="Entra" />
          </div>
        </template>
      </Card>
    </div>
    <Divider v-if="dim.isSmaller('sm')" />
    <div class="w-full h-1/2 sm:h-full bg-emerald-200 sm:flex sm:items-center sm:justify-center">
      <Card
        class="flex items-center justify-center p-4 sm:p-12 h-full sm:h-auto !rounded-none sm:!rounded-2xl">
        <template #title>
          <span class="text-center font-black text-4xl uppercase">Compila un modulo</span>
        </template>
        <template #content>
          <Divider align="center" type="solid">
            <p>Inserisci il codice fornito</p>
          </Divider>
          <div class="flex flex-col items-center gap-2">
            <FloatLabel variant="on" class="w-full">
              <label for="code">Codice</label>
              <InputText id="code" v-model="code" class="w-full" />
            </FloatLabel>
            <Button
              class="w-full"
              :loading="loading"
              icon="pi pi-search"
              label="Cerca modulo"
              @click="codeValid()" />
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>
