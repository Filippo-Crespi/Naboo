<script lang="ts" setup>
definePageMeta({
  layout: "header",
});
const toast = useToast();

onMounted(() => {
  useCookie("user").value = null;
  useCookie("token").value = null;
});

const code = ref("");
const loading = ref(false);
async function search() {
  if (!code.value.match(/^[a-z0-9]{6,6}$/i)) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: "Il codice deve essere di 6 caratteri alfanumerici",
      life: 3000,
    });
  }
}
</script>

<template>
  <Toast />
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

<style scoped></style>
