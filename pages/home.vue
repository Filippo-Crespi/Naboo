<script lang="ts" setup>
import type { Response, FormData } from "~/types";

definePageMeta({
  layout: "header",
});
const toast = useToast();
const isDialogVisible = ref(false);

onMounted(() => {
  useCookie("user").value = null;
  useCookie("token").value = null;
});

const modulo = ref<FormData>({
  Titolo: "",
  Descrizione: "",
});

const code = ref("");
const loading = ref(false);
const search = async () => {
  loading.value = true;
  try {
    const res: Response = await $fetch(
      `https://andrellaveloise.it/forms?ID_Modulo=${code.value}&data=Titolo+Descrizione`,
      {
        method: "GET",
        onResponseError({ response }) {
          throw new Error(response._data.message);
        },
      }
    );
    modulo.value.Titolo = res.data.Titolo;
    modulo.value.Descrizione = res.data.Descrizione;
    isDialogVisible.value = true;
    // redirect al modulo
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
};
</script>

<template>
  <Toast />
  <Dialog
    class="m-w-50"
    modal
    v-model:visible="isDialogVisible"
    pt:mask:class="backdrop-blur-sm"
    header="Modulo trovato">
    <div>
      <p class="text-lg">
        Il modulo <strong>{{ modulo.Titolo }}</strong> è stato trovato con successo.
      </p>
      <p class="mt-2">
        Descrizione: <em>{{ modulo.Descrizione }}</em>
      </p>
      <div class="mt-4 flex justify-end">
        <Button
          label="Compila il modulo"
          icon="pi pi-pencil"
          class="p-button-success"
          @click="isDialogVisible = false"
          disabled />
      </div>
    </div>
  </Dialog>
  <div
    class="h-[100dvh] flex flex-col-reverse sm:flex-row bg-white sm:bg-[url('/imgs/home/background.png')] bg-cover bg-center">
    <div class="h-4/9 sm:w-1/2 sm:h-auto flex flex-col items-center justify-center">
      <Card
        class="px-2 sm:p-8 h-full sm:h-auto w-full sm:w-auto !rounded-none sm:!rounded justify-start">
        <template #title>
          <span class="sm:text-4xl text-2xl font-bold">Crea un modulo</span></template
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
    <div class="h-4/9 sm:w-1/2 sm:h-auto flex flex-col items-center justify-center">
      <Card
        class="px-2 sm:p-8 h-full sm:h-auto w-full sm:w-auto !rounded-none sm:!rounded justify-center">
        <template #title>
          <span class="sm:text-4xl text-2xl font-bold">Compila un modulo</span></template
        >
        <template #subtitle> <span>Codice richiesto</span></template>
        <template #content
          ><div class="flex flex-col gap-2">
            <InputText v-model="code" placeholder="Inserisci il codice" /><Button
              icon="pi pi-search"
              @click="search()"
              label="Cerca"
              :loading="loading" /></div
        ></template>
      </Card>
    </div>
  </div>
</template>
