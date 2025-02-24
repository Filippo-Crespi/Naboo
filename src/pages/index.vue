<script setup>
import { Button, FloatLabel, InputText, Toast, Card, Divider, useToast } from "primevue";
import { ref } from "vue";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import { goTo, compileForm } from "../scripts/helper.js";
import FormAPI from "../services/FormAPI";

const dim = useBreakpoints(breakpointsTailwind);
const toast = useToast();
const code = ref("");
const loading = ref(false);
</script>

<template>
  <Toast />
  <div class="w-[170vw] h-screen bg-[url('/imgs/home/background.png')] bg-cover bg-center relative">
    <div class="absolute top-1/2 transform -translate-y-1/2 translate-x-1/2 max-w-[40vw]">
      <img src="/imgs/home/box-1.png" alt="box-1" />
      <div class="absolute top-1/2 transform -translate-y-1/2 translate-x-1/2">
        <span class="text-center font-black text-4xl uppercase">Crea un modulo</span>
        <Divider align="center" type="solid">
          <p>Registrazione necessaria</p>
        </Divider>
      </div>
    </div>

    <div class="absolute top-1/2 right-0 transform -translate-y-1/2 -translate-x-1/2 max-w-[35vw]">
      <img src="/imgs/home/box-2.png" alt="box-2" />
    </div>
  </div>

  <!-- <div class="w-full h-1/2 sm:h-full sm:flex sm:items-center sm:justify-center">
      <div
        class="container flex flex-col !bg-center !bg-contain bg-no-repeat !bg-[url('/imgs/home/box-1.png')] items-center justify-center p-4 sm:p-12 h-full sm:h-auto !rounded-none sm:!rounded-2xl">
        
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
      </div>
    </div>
    <Divider v-if="dim.isSmaller('sm')" />
    <div class="flex w-full h-1/2 sm:h-full sm:flex sm:items-center sm:justify-center">
      <div
        class="container flex flex-col !bg-center !bg-contain bg-no-repeat !bg-[url('/imgs/home/box-2.png')] items-center justify-center p-4 sm:p-12 h-full sm:h-auto !rounded-none sm:!rounded-2xl">
        <span class="text-center font-black text-4xl uppercase">Compila un modulo</span>
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
            @click="compileForm" />
        </div>
      </div>
    </div> -->
</template>
