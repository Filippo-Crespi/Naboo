<script lang="ts" setup>
import type { Response } from "~/types";
const reloadKey = ref(0);

const toast = useToast();
const session_uuid = useCookie("session_uuid").value;
const isDrawerVisible = ref(false);
const isDialogVisible = ref(false);
const modulo = ref({
  title: "",
  description: "",
  session_uuid,
  anonymous: false,
});

const creaModulo = async () => {
  try {
    const res: Response = await $fetch("http://localhost:8085/api/moduli/utenti.php", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: modulo.value,
      onResponseError({ response }) {
        if (response.status === 400) {
          throw new Error(response._data.message);
        } else if (response.status === 500) {
          throw new Error("Errore del server");
        }
      },
    });
    toast.add({
      severity: "success",
      summary: "Modulo creato",
      detail: res.message,
      life: 2500,
    });
    reloadKey.value++;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 2500,
    });
  } finally {
    isDialogVisible.value = false;
  }
}
</script>

<template>
  <Toast />
  <Dialog modal v-model:visible="isDialogVisible" pt:mask:class="backdrop-blur-sm" class="w-5/6 md:w-[unset]">
    <div class="px-4 pb-8 flex flex-col gap-6">
      <span class="text-3xl text-center md:text-left md:text-4xl font-bold text-[#10b981]">Crea un nuovo modulo</span>
      <FloatLabel variant="on">
        <InputText type="text" fluid v-model="modulo.title" />
        <label>Titolo modulo</label>
      </FloatLabel>
      <FloatLabel variant="on"><Textarea v-model="modulo.description" fluid />
        <label>Descrizione modulo</label>
      </FloatLabel>
      <div class="flex items-center gap-2">
        <Checkbox v-model="modulo.anonymous" :binary="true" inputId="anonimo" />
        <label for="anonimo" class="text-gray-700 select-none cursor-pointer">Modulo anonimo</label>
      </div>
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