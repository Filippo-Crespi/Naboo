<template>
  <div class="p-4">
    <div class="flex items-center gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4" style="border-color: #10b981;">
      <Button icon="pi pi-arrow-left" class="p-button-rounded" as="router-link" to="/dashboard" />
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Compila Modulo</h1>
        <p class="text-sm text-gray-500">Rispondi alle domande e invia il modulo</p>
      </div>
    </div>
    <div v-if="loading" class="text-center text-lg">Caricamento...</div>
    <template v-else>
      <div class="mb-4">
        <h1 class="text-2xl font-bold">{{ modulo.Titolo }}</h1>
        <h2 class="text-xl">{{ modulo.Descrizione }}</h2>
      </div>
      <div>
        <div v-for="(sezione, sezioneIndex) in modulo.Sezioni" :key="sezioneIndex"
          class="mb-6 py-4 px-6 rounded bg-[#10b981]/20">
          <h3 class="text-lg font-semibold">
            {{ sezione.Nome }}
          </h3>
          <div v-for="(domanda, domandaIndex) in sezione.Domande" :key="domandaIndex"
            class="mt-4 border-2 border-[#10b981] p-4 rounded">
            <h4 class="text-md font-medium">
              {{ domanda.Testo }}
            </h4>
            <p class="text-gray-600">{{ domanda.Descrizione }}</p>

            <!-- Autenticazione Email -->
            <InputText v-if="isAutenticazioneEmail(domanda.Tipologia || domanda.IDF_Tipologia)" type="email"
              class="w-full mt-2" placeholder="Inserisci la tua email"
              v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}`]" />
            <!-- Risposta Multipla -->
            <div v-else-if="isRispostaMultipla(domanda.Tipologia || domanda.IDF_Tipologia) && domanda.Risposte"
              class="mt-2 flex flex-col gap-2">
              <div v-for="(risposta, rispostaIndex) in domanda.Risposte" :key="rispostaIndex"
                class="flex items-center gap-2">
                <RadioButton :inputId="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`" :value="rispostaIndex"
                  :name="`d${sezioneIndex}-${domandaIndex}`"
                  v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}`]" />
                <label :for="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`">
                  {{ risposta.Testo }}
                </label>
              </div>
            </div>
            <!-- Vero/Falso -->
            <div v-else-if="isVeroFalso(domanda.Tipologia || domanda.IDF_Tipologia) && domanda.Risposte"
              class="mt-2 flex flex-col gap-2">
              <div v-for="(risposta, rispostaIndex) in domanda.Risposte" :key="rispostaIndex"
                class="flex items-center gap-2">
                <RadioButton :inputId="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`" :value="rispostaIndex"
                  :name="`d${sezioneIndex}-${domandaIndex}`"
                  v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}`]" />
                <label :for="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`">
                  {{ risposta.Testo }}

                </label>
              </div>
            </div>
            <!-- Risposta Breve -->
            <div v-else-if="isRispostaBreve(domanda.Tipologia || domanda.IDF_Tipologia) && domanda.Risposte"
              class="w-full mt-2 flex flex-col gap-2">
              <div v-for="(risposta, rispostaIndex) in domanda.Risposte" :key="risposta.ID_Risposta"
                class="flex items-center gap-2">
                <InputText class="w-full" placeholder="Risposta breve"
                  v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}-${rispostaIndex}`]" />

              </div>
            </div>
            <!-- Risposta Lunga -->
            <div v-else-if="isRispostaLunga(domanda.Tipologia || domanda.IDF_Tipologia)"
              class="w-full mt-2 flex items-center gap-2">
              <Textarea class="w-full" auto-resize placeholder="Risposta lunga"
                v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}`]" />
            </div>
            <!-- Risposta No Limiti -->
            <div v-else-if="isRispostaNoLimiti(domanda.Tipologia || domanda.IDF_Tipologia)"
              class="w-full mt-2 flex items-center gap-2">
              <Textarea class="w-full" auto-resize placeholder="Risposta senza limiti"
                v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}`]" />
            </div>
          </div>
        </div>
        <div class="flex gap-4 mt-4">
          <Button label="Invia Modulo" icon="pi pi-send" class="p-button-success" @click="inviaModulo" />
        </div>
      </div>
    </template>
  </div>
</template>

<script lang="ts" setup>
import type { Response } from '~/types';

// definePageMeta({
//   middleware: ["auth"],
// });

const route = useRoute();
const formCode = route.params.code;
const token = useCookie('token').value;
const toast = useToast();

const modulo = ref<any>(null);
const loading = ref(true);
const risposteUtente = ref<any>({});

// Funzione per normalizzare il modulo secondo lo schema richiesto
function normalizzaModulo(data: any) {
  return {
    Titolo: data.Titolo || data.titolo || '',
    Descrizione: data.Descrizione || data.descrizione || '',
    ID_Modulo: data.ID_Modulo || data.id_modulo || data.IdModulo || '',
    Sezioni: (data.Sezioni || data.sezioni || []).map((sez: any) => ({
      ID_Sezione: sez.ID_Sezione || sez.id_sezione || '',
      Nome: sez.Nome || sez.nome || '',
      Domande: (sez.Domande || sez.domande || []).map((dom: any) => ({
        ID_Domanda: dom.ID_Domanda || dom.id_domanda || '',
        Testo: dom.Testo || dom.testo || '',
        Descrizione: dom.Descrizione || dom.descrizione || '',
        Tipologia: dom.Tipologia || dom.tipologia || dom.IDF_Tipologia || dom.idf_tipologia || 0,
        Risposte: (dom.Risposte || dom.risposte || []).map((ris: any) => ({
          ID_Risposta: ris.ID_Risposta || ris.id_risposta || '',
          Testo: ris.Testo || ris.testo || '',
          Punteggio: ris.Punteggio ?? ris.punteggio ?? 0
        }))
      }))
    }))
  };
}

try {
  const res: Response = await $fetch('https://andrellaveloise.it/forms?Codice=' + formCode, {
    method: 'get',
    onResponseError({ response }) {
      if (response.status === 404) {
        throw new Error('Modulo non trovato');
      }
    },
  });
  modulo.value = normalizzaModulo(res.data);
} catch (error) {
  toast.add({
    severity: 'error',
    summary: 'Errore',
    detail: (error as Error).message,
    life: 2500,
  });
} finally {
  loading.value = false;
}

// Recupera tipologie dal backend
const resTipologie: Response = await $fetch('https://andrellaveloise.it/forms/types?Token=' + token, {
  method: 'get',
});
const Tipologie = resTipologie.data;

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
function isAutenticazioneEmail(tipologia: number | string) {
  return String(tipologia) === '1';
}

function getTipologiaNome(id: number | string | null) {
  const t = Tipologie.find((el: any) => String(el.ID_Tipologia) === String(id));
  return t ? t.Nome : 'Sconosciuta';
}

function selezionaRispostaMultipla(sezioneIdx: number, domandaIdx: number, rispostaIdx: number) {
  const key = `${sezioneIdx}-${domandaIdx}`;
  if (!Array.isArray(risposteUtente.value[key])) risposteUtente.value[key] = [];
  const arr = risposteUtente.value[key];
  const i = arr.indexOf(rispostaIdx);
  if (i === -1) arr.push(rispostaIdx);
  else arr.splice(i, 1);
}

async function inviaModulo() {
  // Costruzione risposte secondo lo schema richiesto dal backend
  const risposte: any[] = [];
  modulo.value.Sezioni.forEach((sezione: any, sezioneIndex: number) => {
    sezione.Domande.forEach((domanda: any, domandaIndex: number) => {
      const tipologia = String(domanda.Tipologia);
      const key = `${sezioneIndex}-${domandaIndex}`;
      const rispostaUtente = risposteUtente.value[key];
      if (isRispostaMultipla(tipologia) && typeof rispostaUtente === 'number') {
        risposte.push({
          ID_Risposta: domanda.Risposte[rispostaUtente]?.ID_Risposta,
          ID_Tipologia: tipologia
        });
      } else if (isVeroFalso(tipologia) && typeof rispostaUtente === 'number') {
        risposte.push({
          ID_Risposta: domanda.Risposte[rispostaUtente]?.ID_Risposta,
          ID_Tipologia: tipologia
        });
      } else if (isRispostaBreve(tipologia) || isRispostaLunga(tipologia) || isRispostaNoLimiti(tipologia)) {
        // Usa sempre l'ID_Risposta reale se presente, anche per risposte aperte
        const idRisposta = domanda.Risposte && domanda.Risposte[0]?.ID_Risposta ? domanda.Risposte[0].ID_Risposta : domanda.ID_Domanda;
        risposte.push({
          ID_Risposta: idRisposta,
          ID_Tipologia: tipologia,
          Testo: rispostaUtente !== undefined ? rispostaUtente : null
        });
      } else if (isAutenticazioneEmail(tipologia)) {
        risposte.push({
          ID_Risposta: domanda.ID_Domanda,
          ID_Tipologia: tipologia,
          Testo: rispostaUtente
        });
      } else if (rispostaUtente !== undefined && rispostaUtente !== null && rispostaUtente !== "") {
        risposte.push({
          ID_Risposta: domanda.ID_Domanda,
          ID_Tipologia: tipologia
        });
      }
    });
  });

  const payload = {
    ID_Modulo: modulo.value.ID_Modulo,
    Risposte: risposte
  };
  try {
    console.log('Payload:', payload);
    const res: Response = await $fetch('https://andrellaveloise.it/forms/compile', {
      method: 'post',
      body: payload,
      onResponseError({ response }) {
        if (response.status === 400) {
          throw new Error('Errore durante l\'invio del modulo');
        }
      },
    });
    if (res.success) {
      toast.add({
        severity: 'success',
        summary: 'Risposte inviate',
        detail: 'Le risposte sono state inviate con successo',
        life: 2500,
      });
      useRouter().push('/home');
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Errore',
      detail: (error as Error).message,
      life: 2500,
    });
  }
}
</script>

<style></style>