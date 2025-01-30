<script setup>
import Input from "@/components/Input.vue";
import Button from "@/components/Button.vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import $ from "jquery";
import { ref } from "vue";
window.$ = $;

const toast = useToast();
</script>

<template>
  <Toast />
  <div class="flex items-center justify-center h-screen w-full">
    <div class="bg-[#83d4c0] p-12 flex flex-col items-center gap-4 rounded-3xl">
      <h1 class="text-center font-bold text-3xl">Registrati</h1>
      <!-- divider -->
      <div>
        <Input type="text" placeholder="Nome" />
      </div>
      <div>
        <Input type="text" placeholder="Cognome" />
      </div>
      <div>
        <Input type="email" placeholder="Email" />
      </div>
      <div>
        <Input type="password" placeholder="Password" />
      </div>
      <Button text="Invia" dim="r" icon="material-symbols:send-rounded" />
    </div>
  </div>
</template>
