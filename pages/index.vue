<script lang="ts" setup>
import { breakpointsTailwind } from "@vueuse/core";
const bp = useBreakpoints(breakpointsTailwind);
const toast = useToast();

const code = ref("");
const loading = ref(false);
async function search() {
  // try {
  //   loading.value = true;
  //   const res = await useFetch("/api/forms/codeValidation", {
  //     method: "POST",
  //     body: JSON.stringify({ code: code.value }),
  //   });
  //   // reindirizza al form
  // } catch (err) {
  //   toast.add({
  //     severity: "error",
  //     summary: "Errore",
  //     detail: (err as Error).message,
  //   });
  // } finally {
  //   loading.value = false;
  // }
  if (!code.value.match(/^[a-z0-9]{6,6}$/i)) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: "Il codice deve essere di 6 caratteri alfanumerici",
    });
  }
}
</script>

<template>
  <Toast />
  <div
    class="h-[100dvh] flex flex-col sm:flex-row justify-around bg-[url('/imgs/home/background.png')] bg-cover bg-center"
    style="background-size: cover">
    <div class="h-1/2 sm:w-1/2 sm:h-auto flex flex-col items-center justify-center">
      <Card class="!p-4 sm:!p-8 sm:max-w-3/4">
        <template #title>
          <span class="sm:text-4xl text-3xl font-bold">Crea un modulo</span></template
        >
        <template #subtitle> <span>Account richiesto</span></template>
        <template #content
          ><div class="flex flex-col sm:flex-row gap-2">
            <Button as="router-link" to="/auth/login" label="Accedi" icon="pi pi-sign-in" />
            <Button
              label="Crea un account"
              icon="pi pi-user-plus"
              as="router-link"
              to="/auth/register" /></div
        ></template>
      </Card>
    </div>
    <div class="h-1/2 sm:w-1/2 sm:h-auto flex flex-col items-center justify-center">
      <Card class="sm:!p-8 !p-4 sm:max-w-3/4">
        <template #title>
          <span class="sm:text-4xl text-3xl font-bold">Compila un modulo</span></template
        >
        <template #subtitle> <span>Codice richiesto</span></template>
        <template #content
          ><div class="flex flex-col sm:flex-row gap-2">
            <InputText v-model="code" placeholder="Inserisci il codice" fluid /><Button
              icon="pi pi-search"
              :label="bp.current().value.length === 0 ? 'Cerca' : ''"
              @click="search()"
              :loading="loading" /></div
        ></template>
      </Card>
    </div>
  </div>
</template>

<style scoped></style>
