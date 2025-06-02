<script lang="ts" setup>
import { v4 } from "uuid";
const reloadKey = ref(0);

const toast = useToast();
const isDrawerVisible = ref(false);
const isDialogVisible = ref(false);
const modulo = ref({
  Titolo: "",
  Descrizione: "",
  Token: "",
});

const creaModulo = async () => {
  try {
    const { data, error } = await useFetch("http://localhost:8085/moduli/modulo.php", {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
      body: null
    });
    if (error) {
      throw new Error(`HTTP error! status: ${error.value?.message}`);
    }
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 2500,
    });
  }
}
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