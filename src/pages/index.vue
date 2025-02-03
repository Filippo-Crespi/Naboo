<script setup>
import { Button, FloatLabel, InputText, Toast } from "primevue";
import { onMounted, ref } from "vue";
import { useToast } from "primevue/usetoast";
import { Divider } from "primevue";
import { goTo } from "../scripts/helper";
import $ from "jquery";

window.$ = $;

const toast = useToast();
const code = ref("");
const loading = ref(false);

onMounted(() => {
  // chiedere cookie
});

function codeRegex(code) {
  return /^[a-zA-Z0-9]{6}$/.test(code);
}

function codeValid() {
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
  $.ajax({
    url: "http://localhost:3000/",
    type: "GET",
    data: { code: code.value },
    success: function (data) {
      if (data.length === 0) {
        loading.value = false;
        toast.add({
          severity: "error",
          summary: "Errore",
          detail: "Modulo non trovato",
          life: 2500,
        });
        return;
      }
      goTo(`/modules/${data[0].id}`);
    },
    error: function () {
      loading.value = false;
      toast.add({
        severity: "error",
        summary: "Errore",
        detail: "Errore di connessione",
        life: 2500,
      });
    },
  });
}
</script>

<template>
  <Toast />
  <div class="flex h-screen w-full">
    <div
      class="w-1/2 h-full bg-[#83d4c0] bg-center bg-cover flex flex-col justify-center items-center">
      <div
        class="min-h-[22rem] min-w-[27rem] p-12 rounded-3xl bg-white flex flex-col justify-center items-center gap-2">
        <div class="text-center">
          <h1 class="text-4xl font-bold">Crea un modulo</h1>
        </div>
        <Divider align="center" type="solid">
          <p>Registrazione necessaria</p>
        </Divider>
        <div class="flex flex-col gap-2 w-1/2">
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
      </div>
    </div>
    <div class="w-1/2 bg-[#8fb2d0] bg-center bg-cover flex justify-center items-center h-full">
      <div
        class="min-h-[22rem] min-w-[27rem] p-12 rounded-3xl bg-white flex flex-col justify-center items-center gap-2">
        <h1 class="text-4xl font-bold text-center">Compila un modulo</h1>
        <Divider align="center" type="solid">
          <p>Inserisci il codice fornito</p>
        </Divider>
        <div class="flex flex-col items-center gap-2">
          <FloatLabel variant="on">
            <label for="code">Codice</label>
            <InputText id="code" v-model="code" />
          </FloatLabel>
          <Button
            class="w-full"
            :loading="loading"
            icon="pi pi-search"
            label="Cerca modulo"
            @click="codeValid()" />
        </div>
      </div>
    </div>
  </div>
</template>
