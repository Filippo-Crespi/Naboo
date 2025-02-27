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
  <div class="flex w-[100dvw] h-screen bg-[url('/imgs/home/background.png')] bg-cover bg-center">
    <div class="w-full h-screen flex items-center justify-center">
      <Card>
        <template #title>Crea un modulo</template>
        <template #subtitle>
          <Divider align="center" type="solid">
            <p>Registrazione necessaria</p>
          </Divider>
        </template>
        <template #content>
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
        </template>
      </Card>
    </div>
    <Divider v-if="dim.isSmaller('sm')" />
    <div class="flex w-full h-full items-center justify-center">
      <Card>
        <template #title>Compila un modulo</template>
        <template #subtitle>
          <Divider align="center" type="solid">
            <p>Inserisci il codice fornito</p>
          </Divider>
        </template>
        <template #content>
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
        </template>
      </Card>
    </div>
  </div>
</template>
