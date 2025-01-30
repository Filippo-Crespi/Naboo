<script setup>
import { ref } from "vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Button from "@/components/Button.vue";
import Input from "@/components/Input.vue";
import { goTo } from "../scripts/helper";
import $ from "jquery";
window.$ = $;

const toast = useToast();
const code = ref("");
const loading = ref(false);

function codeRegex(code) {
  return /^[a-zA-Z0-9]{6}$/.test(code);
}

function codeValid() {
  if (!codeRegex(code.value)) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: "Formato del codice non valido",
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
        class="min-h-[22rem] min-w-[27rem] p-12 rounded-3xl bg-white flex flex-col justify-center items-center gap-4">
        <div class="text-center">
          <h1 class="text-4xl font-bold">Crea un modulo</h1>
          <p class="text-base">Registrazione necessaria</p>
        </div>
        <Button
          @click="goTo('/auth/register')"
          dim="r"
          text="Crea"
          icon="material-symbols:add-diamond-rounded"
          color="#8fb2d0" />
        <p class="mt-4">
          Gi&agrave; registrato?
          <router-link to="/auth/login" class="text-[#8fb2d0] font-bold">Entra</router-link>
        </p>
      </div>
    </div>
    <div class="w-1/2 bg-[#8fb2d0] bg-center bg-cover flex justify-center items-center h-full">
      <div
        class="min-h-[22rem] min-w-[27rem] p-12 rounded-3xl bg-white flex flex-col justify-center items-center gap-4">
        <h1 class="text-4xl font-bold text-center">Compila un modulo</h1>
        <div class="flex justify-center items-center gap-1">
          <Input v-model="code" placeholder="Codice" type="text" />
          <Button
            @click="codeValid()"
            dim="r"
            :icon="loading ? 'line-md:loading-loop' : 'material-symbols:send-rounded'"
            color="#83d4c0" />
        </div>
      </div>
    </div>
  </div>
</template>
