<script lang="ts" setup>
import type { Response } from '~/types';

definePageMeta({
  middleware: ["auth"],
});

const route = useRoute();
const token = useCookie('token').value;
const formCode = route.params.code;

// Recupera modulo dal backend
const modulo = ref<any>(null);
const loading = ref(true);

const res: Response = await $fetch('https://andrellaveloise.it/forms?Codice=' + formCode, {
  method: 'get',
});
modulo.value = res.data;
loading.value = false;

// Recupera tipologie dal backend
const resTipologie: Response = await $fetch('https://andrellaveloise.it/forms/types?Token=' + token, {
  method: 'get',
});
const Tipologie = resTipologie.data;

function getTipologiaNome(id: number | string | null) {
  const t = Tipologie.find((el: any) => String(el.ID_Tipologia) === String(id));
  return t ? t.Nome : 'Sconosciuta';
}

function isRispostaMultipla(tipologia: number | string) {
  return String(tipologia) === '4';
}
function isVeroFalso(tipologia: number | string) {
  return String(tipologia) === '5';
}
function isRispostaTestuale(tipologia: number | string) {
  return ['1', '6', '7', '8'].includes(String(tipologia));
}
</script>

<template>
  <div class="p-4">
    <!-- Cute header con accento verde e pulsanti -->
    <div class="flex items-center justify-start gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4"
      style="border-color: #10b981;">
      <div class="flex gap-2">
        <Button icon="pi pi-arrow-left" class="p-button-rounded" as="router-link" to="/dashboard" />
        <Button icon="pi pi-pencil" severity="warn" outlined class="p-button-rounded" as="router-link"
          :to="`/edit/${route.params.code}`" />
      </div>
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-gray-800">Anteprima Modulo</h1>
        <p class="hidden md:block text-sm text-gray-500">Stai visualizzando la versione di anteprima di questo modulo.
          Puoi modificarlo
          cliccando sull'icona matita.</p>
      </div>
    </div>
    <div v-if="loading" class="text-center text-lg">Caricamento...</div>
    <template v-else>
      <!-- Titolo e descrizione del modulo -->
      <div class="mb-4">
        <h1 class="text-2xl font-bold">{{ modulo.Titolo }}</h1>
        <h2 class="text-xl">{{ modulo.Descrizione }}</h2>
      </div>
      <!-- Sezioni -->
      <div v-for="(sezione, sezioneIndex) in modulo.Sezioni" :key="sezioneIndex"
        class="mb-6 border border-gray-400 p-4 rounded">
        <h3 class="text-lg font-semibold">{{ sezione.Nome }}</h3>
        <!-- Domande -->
        <div v-for="(domanda, domandaIndex) in sezione.Domande" :key="domandaIndex"
          class="mt-4 border border-gray-400 p-4 rounded">
          <h4 class="text-md font-medium">Domanda: {{ domanda.Testo }}</h4>
          <p class="text-gray-600">{{ domanda.Descrizione }}</p>
          <div class="text-xs text-gray-400 mb-2">Tipo: {{ getTipologiaNome(domanda.Tipologia || domanda.IDF_Tipologia)
          }}</div>
          <!-- Risposte -->
          <div
            v-if="isRispostaMultipla(domanda.Tipologia || domanda.IDF_Tipologia) || isVeroFalso(domanda.Tipologia || domanda.IDF_Tipologia)"
            class="mt-2 flex flex-col gap-2">
            <div v-for="(risposta, rispostaIndex) in domanda.Risposte" :key="rispostaIndex"
              class="flex items-center gap-2">
              <RadioButton :inputId="`domanda-${domandaIndex}-risposta-${rispostaIndex}`" :value="risposta.Punteggio"
                :name="`domanda-${domandaIndex}`" disabled />
              <label :for="`domanda-${domandaIndex}-risposta-${rispostaIndex}`">{{ risposta.Testo }}</label>
            </div>
          </div>
          <InputText v-else-if="String(domanda.Tipologia || domanda.IDF_Tipologia) === '6'" class="w-full mt-2" disabled
            placeholder="Risposta breve (compilazione utente)" />
          <Textarea
            v-else-if="String(domanda.Tipologia || domanda.IDF_Tipologia) === '7' || String(domanda.Tipologia || domanda.IDF_Tipologia) === '8'"
            class="w-full mt-2" :auto-resize="true" disabled
            placeholder="Risposta lunga o senza limiti (compilazione utente)" />
          <div v-else-if="String(domanda.Tipologia || domanda.IDF_Tipologia) === '1'" class="italic text-gray-400 mt-2">
            Campo autenticazione email (compilazione utente)</div>
        </div>
      </div>
    </template>
  </div>
</template>

<style></style>