<template>
  <div class="p-4">
    <div class="flex items-center gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4" style="border-color: #10b981;">
      <Button icon="pi pi-arrow-left" class="p-button-rounded" as="router-link" to="/dashboard" />
      <div>
        <h1 class="text-2xl font-bold">{{ modulo?.Titolo }}</h1>
        <h2 class="text-xl">{{ modulo?.Descrizione }}</h2>
      </div>
    </div>
    <div v-if="loading" class="text-center text-lg">Caricamento...</div>
    <template v-else>
      <div class="mb-4">
        <h1 class="text-2xl font-bold">{{ modulo?.Titolo }}</h1>
        <h2 class="text-xl">{{ modulo?.Descrizione }}</h2>
      </div>
      <div>
        <div v-for="(sezione, sezioneIndex) in modulo?.Sezioni || []" :key="sezioneIndex"
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
            <InputText v-if="isAutenticazioneEmail(domanda.Tipologia ?? domanda.IDF_Tipologia ?? 0)" type="email"
              class="w-full mt-2" placeholder="Inserisci la tua email"
              :modelValue="typeof risposteUtente[`${sezioneIndex}-${domandaIndex}`] === 'string' ? risposteUtente[`${sezioneIndex}-${domandaIndex}`] : ''"
              @update:modelValue="val => risposteUtente[`${sezioneIndex}-${domandaIndex}`] = typeof val === 'string' ? val : ''" />
            <!-- Risposta Multipla -->
            <div v-else-if="isRispostaMultipla(domanda.Tipologia ?? domanda.IDF_Tipologia ?? 0) && domanda.Risposte"
              class="mt-2 flex flex-col gap-2">
              <div v-for="(risposta, rispostaIndex) in domanda.Risposte" :key="rispostaIndex"
                class="flex items-center gap-2">
                <RadioButton :inputId="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`" :value="rispostaIndex"
                  :name="`d${sezioneIndex}-${domandaIndex}`"
                  :modelValue="typeof risposteUtente[`${sezioneIndex}-${domandaIndex}`] === 'number' ? risposteUtente[`${sezioneIndex}-${domandaIndex}`] : null"
                  @update:modelValue="val => risposteUtente[`${sezioneIndex}-${domandaIndex}`] = typeof val === 'number' ? val : null" />
                <label :for="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`">
                  {{ risposta.Testo }}
                </label>
              </div>
            </div>
            <!-- Vero/Falso -->
            <div v-else-if="isVeroFalso(domanda.Tipologia ?? domanda.IDF_Tipologia ?? 0) && domanda.Risposte"
              class="mt-2 flex flex-col gap-2">
              <div v-for="(risposta, rispostaIndex) in domanda.Risposte" :key="rispostaIndex"
                class="flex items-center gap-2">
                <RadioButton :inputId="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`" :value="rispostaIndex"
                  :name="`d${sezioneIndex}-${domandaIndex}`"
                  :modelValue="typeof risposteUtente[`${sezioneIndex}-${domandaIndex}`] === 'number' ? risposteUtente[`${sezioneIndex}-${domandaIndex}`] : null"
                  @update:modelValue="val => risposteUtente[`${sezioneIndex}-${domandaIndex}`] = typeof val === 'number' ? val : null" />
                <label :for="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`">
                  {{ risposta.Testo }}
                </label>
              </div>
            </div>
            <!-- Risposta Breve -->
            <div v-else-if="isRispostaBreve(domanda.Tipologia ?? domanda.IDF_Tipologia ?? 0) && domanda.Risposte"
              class="w-full mt-2 flex flex-col gap-2">
              <div v-for="(risposta, rispostaIndex) in domanda.Risposte" :key="risposta.ID_Risposta"
                class="flex items-center gap-2">
                <InputText class="w-full" placeholder="Risposta breve"
                  :modelValue="typeof risposteUtente[`${sezioneIndex}-${domandaIndex}-${rispostaIndex}`] === 'string' ? risposteUtente[`${sezioneIndex}-${domandaIndex}-${rispostaIndex}`] : ''"
                  @update:modelValue="val => risposteUtente[`${sezioneIndex}-${domandaIndex}-${rispostaIndex}`] = typeof val === 'string' ? val : ''" />
              </div>
            </div>
            <!-- Risposta Lunga -->
            <div v-else-if="isRispostaLunga(domanda.Tipologia ?? domanda.IDF_Tipologia ?? 0)"
              class="w-full mt-2 flex items-center gap-2">
              <Textarea class="w-full" auto-resize placeholder="Risposta lunga"
                :modelValue="typeof risposteUtente[`${sezioneIndex}-${domandaIndex}`] === 'string' ? risposteUtente[`${sezioneIndex}-${domandaIndex}`] : ''"
                @update:modelValue="val => risposteUtente[`${sezioneIndex}-${domandaIndex}`] = typeof val === 'string' ? val : ''" />
            </div>
            <!-- Risposta No Limiti -->
            <div v-else-if="isRispostaNoLimiti(domanda.Tipologia ?? domanda.IDF_Tipologia ?? 0)"
              class="w-full mt-2 flex items-center gap-2">
              <Textarea class="w-full" auto-resize placeholder="Risposta senza limiti"
                :modelValue="typeof risposteUtente[`${sezioneIndex}-${domandaIndex}`] === 'string' ? risposteUtente[`${sezioneIndex}-${domandaIndex}`] : ''"
                @update:modelValue="val => risposteUtente[`${sezioneIndex}-${domandaIndex}`] = typeof val === 'string' ? val : ''" />
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
import type { Response, ModuloCompleto, RispostaUtente, SezioneModulo, DomandaModulo, RispostaOpzione } from '~/types';

// definePageMeta({
//   middleware: ["auth"],
// });

const route = useRoute();
const formCode = route.params.code;
const token = useCookie('token').value;
const toast = useToast();

const modulo = ref<ModuloCompleto | null>(null);
const loading = ref(true);
const risposteUtente = ref<RispostaUtente>({});

// Funzione per normalizzare il modulo secondo lo schema richiesto
function normalizzaModulo(data: any): ModuloCompleto {
  return {
    Titolo: data.Titolo || data.titolo || '',
    Descrizione: data.Descrizione || data.descrizione || '',
    ID_Modulo: data.ID_Modulo || data.id_modulo || data.IdModulo || '',
    Sezioni: (data.Sezioni || data.sezioni || []).map((sez: any): SezioneModulo => ({
      ID_Sezione: sez.ID_Sezione || sez.id_sezione || '',
      Nome: sez.Nome || sez.nome || '',
      Domande: (sez.Domande || sez.domande || []).map((dom: any): DomandaModulo => ({
        ID_Domanda: dom.ID_Domanda || dom.id_domanda || '',
        Testo: dom.Testo || dom.testo || '',
        Descrizione: dom.Descrizione || dom.descrizione || '',
        Tipologia: dom.Tipologia || dom.tipologia || dom.IDF_Tipologia || dom.idf_tipologia || 0,
        Risposte: (dom.Risposte || dom.risposte || []).map((ris: any): RispostaOpzione => ({
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
  const t = Tipologie.find((el: { ID_Tipologia: number }) => String(el.ID_Tipologia) === String(id));
  return t ? t.Nome : 'Sconosciuta';
}

function selezionaRispostaMultipla(sezioneIdx: number, domandaIdx: number, rispostaIdx: number) {
  const key = `${sezioneIdx}-${domandaIdx}`;
  if (!Array.isArray(risposteUtente.value[key])) risposteUtente.value[key] = [];
  const arr = risposteUtente.value[key] as number[];
  const i = arr.indexOf(rispostaIdx);
  if (i === -1) arr.push(rispostaIdx);
  else arr.splice(i, 1);
}

async function inviaModulo() {
  // Costruzione risposte secondo lo schema richiesto dal backend
  const risposte: Array<{ ID_Risposta: string | number | undefined; ID_Tipologia: string | number; Testo: string | null }> = [];
  modulo.value?.Sezioni.forEach((sezione: SezioneModulo, sezioneIndex: number) => {
    sezione.Domande.forEach((domanda: DomandaModulo, domandaIndex: number) => {
      const tipologia = String(domanda.Tipologia);
      const key = `${sezioneIndex}-${domandaIndex}`;
      const rispostaUtente = risposteUtente.value[key];
      if (isRispostaMultipla(tipologia) && typeof rispostaUtente === 'number') {
        risposte.push({
          ID_Risposta: domanda.Risposte?.[rispostaUtente]?.ID_Risposta,
          ID_Tipologia: tipologia,
          Testo: null
        });
      } else if (isVeroFalso(tipologia) && typeof rispostaUtente === 'number') {
        risposte.push({
          ID_Risposta: domanda.Risposte?.[rispostaUtente]?.ID_Risposta,
          ID_Tipologia: tipologia,
          Testo: null
        });
      } else if (isRispostaBreve(tipologia) && domanda.Risposte && domanda.Risposte.length > 0) {
        (domanda.Risposte as RispostaOpzione[]).forEach((risposta: RispostaOpzione, rispostaIndex: number) => {
          const keyBreve = `${sezioneIndex}-${domandaIndex}-${rispostaIndex}`;
          risposte.push({
            ID_Risposta: risposta.ID_Risposta,
            ID_Tipologia: tipologia,
            Testo: risposteUtente.value[keyBreve] !== undefined ? String(risposteUtente.value[keyBreve]) : null
          });
        });
      } else if (isRispostaLunga(tipologia) && domanda.Risposte && domanda.Risposte.length > 0) {
        const keyLunga = `${sezioneIndex}-${domandaIndex}`;
        risposte.push({
          ID_Risposta: domanda.Risposte[0].ID_Risposta,
          ID_Tipologia: tipologia,
          Testo: risposteUtente.value[keyLunga] !== undefined ? String(risposteUtente.value[keyLunga]) : null
        });
      } else if (isRispostaNoLimiti(tipologia) && domanda.Risposte && domanda.Risposte.length > 0) {
        const keyNoLimiti = `${sezioneIndex}-${domandaIndex}`;
        risposte.push({
          ID_Risposta: domanda.Risposte[0].ID_Risposta,
          ID_Tipologia: tipologia,
          Testo: risposteUtente.value[keyNoLimiti] !== undefined ? String(risposteUtente.value[keyNoLimiti]) : null
        });
      } else if (isAutenticazioneEmail(tipologia)) {
        risposte.push({
          ID_Risposta: domanda.ID_Domanda,
          ID_Tipologia: tipologia,
          Testo: typeof rispostaUtente === 'string' ? rispostaUtente : null
        });
      } else if (rispostaUtente !== undefined && rispostaUtente !== null && rispostaUtente !== "") {
        risposte.push({
          ID_Risposta: domanda.ID_Domanda,
          ID_Tipologia: tipologia,
          Testo: null
        });
      }
    });
  });

  const payload = {
    ID_Modulo: modulo.value?.ID_Modulo,
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