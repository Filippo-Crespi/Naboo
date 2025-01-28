<script setup>
import { ref } from "vue";
import axios from "axios";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Button from "@/components/Button.vue";
import Input from "@/components/Input.vue";
import { goTo } from "../scripts/helper";

const toast = useToast();
const code = ref("");

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

  // richiesta di ricerca del modulo
  axios
    .get(`http://localhost/Forms/src/api/-.php`, {
      params: {
        code: code,
      },
    })
    .then((res) => {
      if (res.status === 200) {
        toast.add({
          severity: "success",
          summary: "Successo",
          detail: "Modulo trovato",
        });
        //redirect al modulo
      }
      toast.add({
        severity: "error",
        summary: "Errore",
        detail: "Errore durante la ricerca del modulo",
      });
    });
}
</script>

<template>
  <Toast />
  <div class="flex h-screen w-full">
    <div
      class="w-1/2 p-2 h-full bg-[#83d4c0] bg-center bg-cover flex flex-col justify-center items-center">
      <div
        class="p-16 rounded-3xl backdrop-blur-2xl border-2 border-violet-700 flex flex-col justify-center items-center gap-4">
        <h1 class="text-4xl font-bold">Crea un modulo</h1>
        <p class="text-base">Registrazione necessaria</p>
        <Button
          @click="goTo('/auth/register')"
          dim="r"
          text="Crea"
          icon="material-symbols:add-diamond-rounded"
          color="#8fb2d0" />
        <p class="mt-4">
          Gi&agrave; registrato?
          <router-link to="/auth/login" class="text-white">Entra</router-link>
        </p>
      </div>
    </div>
    <div class="w-1/2 p-2 bg-[#8fb2d0] bg-center bg-cover flex justify-center items-center h-full">
      <div
        class="p-16 rounded-3xl backdrop-blur-2xl border-2 border-violet-700 flex flex-col justify-center items-center gap-4">
        <h1 class="text-4xl font-bold">Compila un modulo</h1>
        <div class="flex justify-center items-center gap-1">
          <Input v-model="code" placeholder="Codice" type="text" />
          <Button
            @click="codeValid()"
            dim="r"
            icon="material-symbols:search-rounded"
            color="#83d4c0" />
        </div>
      </div>
    </div>
  </div>
</template>
