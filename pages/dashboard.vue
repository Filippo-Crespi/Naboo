<script lang="ts" setup>
import { v4 } from "uuid";
import { type Form, type Response } from "~/types";
definePageMeta({
  middleware: ["auth"],
});

const reloadKey = ref(0);

const toast = useToast();
const isDrawerVisible = ref(false);
const isDialogVisible = ref(false);
const modulo = ref<Form>({
  Titolo: "",
  Descrizione: "",
  Token: "",
});

const creaModulo = async () => {
  const token = useCookie("token").value;
  try {
    const res: Response = await $fetch("https://andrellaveloise.it/forms/users", {
      method: "POST",
      body: {
        Token: token,
        Titolo: modulo.value.Titolo,
        Descrizione: modulo.value.Descrizione,
        Codice: v4()
      },
      onResponseError({ response }) {
        if (response.status === 404) {
          throw new Error(response._data.message);
        }
      },
    });
    const code = res.data.Codice;
    toast.add({
      severity: "success",
      summary: "Modulo creato",
      detail: "Usa il codice: " + code + " per condividere il modulo",
    });
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 2500,
    });
    isDialogVisible.value = false;
  } finally {
    isDialogVisible.value = false;
    reloadKey.value++;
  }
};
</script>
<template>
  <Toast />
  <Dialog modal v-model:visible="isDialogVisible" pt:mask:class="backdrop-blur-sm" class="w-5/6 md:w-[unset]">
    <div class="px-4 pb-8 flex flex-col gap-6">
      <span class="text-3xl text-center md:text-left md:text-4xl font-bold text-[#10b981]">Crea un nuovo modulo</span>
      <FloatLabel variant="on">
        <InputText type="text" fluid v-model="modulo.Titolo" />
        <label>Titolo modulo</label>
      </FloatLabel>
      <FloatLabel variant="on"><Textarea v-model="modulo.Descrizione" fluid />
        <label>Descrizione modulo</label>
      </FloatLabel>
      <Button class="w-full" label="Crea" icon="pi pi-plus" severity="primary" raised @click="creaModulo" />
    </div>
  </Dialog>
  <AccountDrawer v-model:visible="isDrawerVisible" />
  <div class="flex flex-col justify-between min-h-[calc(100vh)]">
    <div>
      <DashboardToolbar @open-drawer="isDrawerVisible = true" @create-form="isDialogVisible = true" />
      <FormContainer :key="reloadKey" @refresh="reloadKey++" />
    </div>
    <div class="hidden md:block w-full fixed bottom-0 left-0 mt-8">
      <Button label="Sincronizza" fluid icon="pi pi-refresh" severity="secondary" @click="reloadKey++" />
    </div>
  </div>
</template>

<style></style>