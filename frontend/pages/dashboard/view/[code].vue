<script lang="ts" setup>
import { type Form, type Section, type Response } from '~/types';

definePageMeta({
  middleware: ["auth"],
});

const route = useRoute();
const formCode = route.params.code;
const session_uuid = useCookie('session_uuid').value;

const modulo = ref<Form | null>(null);
const loading = ref(true);
const toast = useToast();

// Carica modulo dal backend
try {
  const res: Response = await $fetch('http://localhost:8085/api/moduli/modulo.php?code=' + formCode, {
    method: 'get',
  });
  modulo.value = res.data;
} catch (error) {
  modulo.value = null;
  toast.add({
    severity: 'error',
    summary: 'Errore',
    detail: 'Impossibile caricare il modulo.',
    life: 2500,
  });
  modulo.value = {
    title: '',
    description: '',
    data: {
      sections: [
        {
          title: '',
          questions: [
            {
              label: '',
              description: '',
              type: 4,
              options: [
                { label: '', score: 0 },
              ],
            },
          ],
        },
      ],
    },
  };
} finally {
  loading.value = false;
}

// Tipologie domanda (allineate a edit)
const types = [
  { id_type: 1, text: 'Autenticazione' },
  { id_type: 4, text: 'Risposta Multipla' },
  { id_type: 5, text: 'Vero Falso' },
  { id_type: 6, text: 'Risposta breve' },
  { id_type: 7, text: 'Risposta lunga' },
  { id_type: 8, text: 'Risposta senza limiti' },
];

function getTipologiaNome(id: number | string | null) {
  const t = types.find((el) => String(el.id_type) === String(id));
  return t ? t.text : 'Sconosciuta';
}

function isRispostaMultipla(tipologia: number | string) {
  return String(tipologia) === '4';
}
function isVeroFalso(tipologia: number | string) {
  return String(tipologia) === '5';
}
function isRispostaBreve(tipologia: number | string) {
  return String(tipologia) === '6';
}
function isRispostaLunga(tipologia: number | string) {
  return String(tipologia) === '7';
}
function isRispostaNoLimiti(tipologia: number | string) {
  return String(tipologia) === '8';
}
function isAutenticazione(tipologia: number | string) {
  return String(tipologia) === '1';
}
</script>

<template>
  <Toast />
  <div class="p-4">
    <!-- Header coerente -->
    <div class="flex items-center gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4" style="border-color: #10b981;">
      <Button icon="pi pi-arrow-left" class="p-button-rounded p-button-secondary" as="router-link" to="/dashboard">
        <Icon name="solar:arrow-left-bold-duotone" />
      </Button>
      <Button severity="warn" outlined class="p-button-rounded h-full" as="router-link"
        :to="`/dashboard/edit/${route.params.code}`">
        <Icon name="solar:pen-bold-duotone" />
      </Button>
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Anteprima Modulo</h1>
        <p class="hidden md:block text-sm text-gray-500">Stai visualizzando la versione di anteprima di questo modulo.
          Puoi modificarlo cliccando sull'icona matita.</p>
      </div>
    </div>
    <div v-if="loading" class="text-center text-lg">Caricamento...</div>
    <template v-else>
      <div v-if="modulo">
        <!-- Titolo e descrizione -->
        <div class="mb-4">
          <h1 class="text-2xl font-bold">{{ modulo.title }}</h1>
          <h2 class="text-xl">{{ modulo.description }}</h2>
        </div>
        <!-- Sezioni -->
        <div v-for="(sezione, sezioneIndex) in modulo.data?.sections" :key="sezioneIndex"
          class="mb-6 border border-gray-400 p-4 rounded">
          <h3 class="text-lg font-semibold">{{ sezione.title }}</h3>
          <!-- Domande -->
          <div v-for="(domanda, domandaIndex) in sezione.questions" :key="domandaIndex"
            class="mt-4 border border-gray-400 p-4 rounded">
            <h4 class="text-md font-medium">Domanda: {{ domanda.label }}</h4>
            <p class="text-gray-600">{{ domanda.description }}</p>
            <div class="text-xs text-gray-400 mb-2">Tipo: {{ getTipologiaNome(domanda.type) }}</div>
            <!-- Risposte -->
            <div v-if="isRispostaMultipla(domanda.type) || isVeroFalso(domanda.type)" class="mt-2 flex flex-col gap-2">
              <div v-for="(risposta, rispostaIndex) in domanda.options ?? []" :key="rispostaIndex"
                class="flex items-center gap-2">
                <RadioButton :inputId="`domanda-${domandaIndex}-risposta-${rispostaIndex}`" :value="risposta.score"
                  :name="`domanda-${domandaIndex}`" disabled />
                <label :for="`domanda-${domandaIndex}-risposta-${rispostaIndex}`">{{ risposta.label }}</label>
                <span class="text-xs text-gray-500 ml-2">(Punteggio: {{ risposta.score }})</span>
              </div>
              <!-- Anteprima risposte -->
              <div class="mt-4 p-3 rounded border border-blue-300 bg-blue-50">
                <div class="font-semibold mb-2 text-blue-800">Anteprima risposte:</div>
                <ul class="list-disc pl-5">
                  <li v-for="(risposta, idx) in domanda.options ?? []" :key="'preview-' + idx">
                    <span class="text-gray-800">{{ risposta.label }}</span>
                    <span class="text-xs text-gray-500 ml-2">(Punteggio: {{ risposta.score }})</span>
                  </li>
                </ul>
              </div>
            </div>
            <!-- Risposta breve -->
            <div v-else-if="isRispostaBreve(domanda.type)" class="w-full mt-2 flex items-center gap-2">
              <InputText disabled class="w-full" placeholder="Risposta breve (compilazione utente)"
                :value="domanda.options?.[0]?.label ?? ''" />
              <InputText type="number" class="w-40" placeholder="Punteggio"
                :value="String(domanda.options?.[0]?.score ?? '')" disabled />
            </div>
            <!-- Risposta lunga -->
            <div v-else-if="isRispostaLunga(domanda.type)" class="w-full mt-2 flex items-center gap-2">
              <Textarea disabled class="w-full" auto-resize placeholder="Risposta lunga (compilazione utente)"
                :modelValue="domanda.options?.[0]?.label ?? ''" />
              <InputText type="number" class="w-40" placeholder="Punteggio"
                :value="String(domanda.options?.[0]?.score ?? '')" disabled />
            </div>
            <!-- Risposta senza limiti -->
            <div v-else-if="isRispostaNoLimiti(domanda.type)" class="w-full mt-2 flex items-center gap-2">
              <Textarea disabled class="w-full" auto-resize placeholder="Risposta senza limiti (compilazione utente)"
                :modelValue="domanda.options?.[0]?.label ?? ''" />
              <InputText type="number" class="w-40" placeholder="Punteggio"
                :value="String(domanda.options?.[0]?.score ?? '')" disabled />
            </div>
            <!-- Autenticazione -->
            <div v-else-if="isAutenticazione(domanda.type)" class="italic text-gray-400 mt-2">
              Campo autenticazione email (compilazione utente)
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-center text-red-500">Modulo non trovato o errore di caricamento.</div>
    </template>
  </div>
</template>

<style></style>