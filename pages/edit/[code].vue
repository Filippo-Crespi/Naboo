<script lang="ts" setup>
import type { Response } from '~/types';


definePageMeta({
  middleware: ["auth"],
});

const route = useRoute();
const token = useCookie('token').value
const toast = useToast();

const formCode = route.params.code;

const res1: Response = await $fetch('https://andrellaveloise.it/forms/types?Token=' + token, {
  method: 'get',
})

const Tipologie = res1.data
console.log(Tipologie)

const modulo = ref()
try {
  const res: Response = await $fetch('https://andrellaveloise.it/forms?Codice=' + formCode, {
    method: 'get',
    onResponseError({ response }) {
      if (response.status === 404) {
        modulo.value = {
          Titolo: response._data.data?.Titolo || '',
          Descrizione: response._data.data?.Descrizione || '',
          Sezioni: [
            {
              Nome: '',
              Domande: [
                {
                  Testo: '',
                  Descrizione: '',
                  Tipologia: null,
                  Risposte: [
                    {
                      Testo: '',
                      Punteggio: 0,
                    },
                  ],
                },
              ],
            },
          ],
        };
        throw new Error('Modulo non trovato, creato oggetto vuoto con titolo e descrizione.');
      }
    },
  })
  modulo.value = res.data
} catch (error) {
  // Se modulo.value non è già stato impostato dal 404, fallback generico
  if (!modulo.value) {
    modulo.value = {
      Titolo: '',
      Descrizione: '',
      Sezioni: [], // <-- Inizializza sempre come array vuoto
    }
  } else if (!modulo.value.Sezioni) {
    // Se per qualche motivo Sezioni non è stato impostato
    modulo.value.Sezioni = [];
  }
} finally {
  console.log(modulo.value)
}

// Dopo il caricamento del modulo, assicura che le risposte siano coerenti con la tipologia
if (modulo.value && Array.isArray(modulo.value.Sezioni)) {
  modulo.value.Sezioni.forEach((sezione: any, sezioneIndex: number) => {
    if (Array.isArray(sezione.Domande)) {
      sezione.Domande.forEach((domanda: any, domandaIndex: number) => {
        if (domanda.IDF_Tipologia) {
          aggiornaTipologiaDomanda(sezioneIndex, domandaIndex, domanda.IDF_Tipologia);
        }
      });
    }
  });
}

// Funzioni per aggiungere sezioni, domande e risposte

function aggiungiSezione() {
  modulo.value.Sezioni.push({
    Nome: '',
    Domande: [
      {
        Testo: '',
        Descrizione: '',
        Tipologia: null,
        Risposte: [
          {
            Testo: '',
            Punteggio: 0,
          },
        ],
      },
    ],
  });
}

function aggiungiDomanda(sezioneIndex: number) {
  modulo.value.Sezioni[sezioneIndex].Domande.push({
    Testo: '',
    Descrizione: '',
    Tipologia: null,
    Risposte: [
      {
        Testo: '',
        Punteggio: 0,
      },
    ],
  });
}

function aggiungiRisposta(sezioneIndex: number, domandaIndex: number) {
  const domanda = modulo.value.Sezioni[sezioneIndex].Domande[domandaIndex];

  if (domanda.Tipologia === 4) { // Scelta Multipla
    if (!domanda.Risposte) domanda.Risposte = [];
    domanda.Risposte.push({ Testo: '', Punteggio: 0 });
  } else {
    console.warn('Le risposte multiple possono essere aggiunte solo per domande di tipo Scelta Multipla.');
  }
}

// Funzione per aggiornare la tipologia di una domanda e resettare le risposte
function aggiornaTipologiaDomanda(sezioneIndex: number, domandaIndex: number, nuovaTipologia: number | string) {
  const tipologiaNum = typeof nuovaTipologia === 'string' ? parseInt(nuovaTipologia) : nuovaTipologia;
  const domanda = modulo.value.Sezioni[sezioneIndex].Domande[domandaIndex];
  domanda.Tipologia = tipologiaNum;
  domanda.IDF_Tipologia = tipologiaNum;

  if (tipologiaNum === 4) { // RispostaMultipla
    // Inizializza solo se non esistono risposte o sono vuote
    if (!Array.isArray(domanda.Risposte) || domanda.Risposte.length === 0) {
      domanda.Risposte = [{ Testo: '', Punteggio: 0 }];
    }
    delete domanda.RispostaAperta;
    delete domanda.RispostaBreve;
  } else if (tipologiaNum === 5) { // VeroFalso
    domanda.Risposte = [
      { Testo: 'Vero', Punteggio: 1 },
      { Testo: 'Falso', Punteggio: 0 },
    ];
    delete domanda.RispostaAperta;
    delete domanda.RispostaBreve;
  } else if (tipologiaNum === 6) { // RispostaBreve
    domanda.Risposte = undefined;
    domanda.RispostaBreve = '';
    delete domanda.RispostaAperta;
  } else if (tipologiaNum === 7) { // RispostaLunga
    domanda.Risposte = undefined;
    domanda.RispostaAperta = '';
    delete domanda.RispostaBreve;
  } else if (tipologiaNum === 8) { // RispostaNoLimiti
    domanda.Risposte = undefined;
    domanda.RispostaAperta = '';
    delete domanda.RispostaBreve;
  } else {
    domanda.Risposte = undefined;
    delete domanda.RispostaAperta;
    delete domanda.RispostaBreve;
  }
}
async function inviaModulo() {
  // Clona il modulo per non modificare l'originale
  const moduloDaInviare = {
    Titolo: modulo.value.Titolo,
    Descrizione: modulo.value.Descrizione,
    Sezioni: modulo.value.Sezioni.map((sezione: any) => ({
      Nome: sezione.Nome,
      Domande: sezione.Domande.map((domanda: any) => {
        const base = {
          Testo: domanda.Testo,
          Descrizione: domanda.Descrizione,
          Tipologia: domanda.IDF_Tipologia || domanda.Tipologia
        };
        // Domande con risposte a scelta multipla o vero/falso
        if (domanda.IDF_Tipologia === 4 || domanda.IDF_Tipologia === 5) {
          return {
            ...base,
            Risposte: (domanda.Risposte || []).map((r: any) => ({
              Testo: r.Testo,
              Punteggio: r.Punteggio
            }))
          };
        }
        // Domande testuali: inserisci una sola risposta se presente
        if (domanda.IDF_Tipologia === 6 && domanda.RispostaBreve) {
          return {
            ...base,
            Risposte: [{ Testo: domanda.RispostaBreve, Punteggio: domanda.PunteggioBreve }]
          };
        }
        if (domanda.IDF_Tipologia === 7 && domanda.RispostaLunga) {
          return {
            ...base,
            Risposte: [{ Testo: domanda.RispostaLunga, Punteggio: domanda.PunteggioLunga }]
          };
        }
        if (domanda.IDF_Tipologia === 8 && domanda.RispostaNoLimiti) {
          return {
            ...base,
            Risposte: [{ Testo: domanda.RispostaNoLimiti, Punteggio: domanda.PunteggioNoLimiti }]
          };
        }
        // Domanda aperta (senza testo): inserisci risposta con Testo null e punteggio
        if ((domanda.IDF_Tipologia === 6 && !domanda.RispostaBreve) ||
          (domanda.IDF_Tipologia === 7 && !domanda.RispostaLunga) ||
          (domanda.IDF_Tipologia === 8 && !domanda.RispostaNoLimiti)) {
          let punteggio = null;
          if (domanda.IDF_Tipologia === 6) punteggio = domanda.PunteggioBreve ?? 0;
          if (domanda.IDF_Tipologia === 7) punteggio = domanda.PunteggioLunga ?? 0;
          if (domanda.IDF_Tipologia === 8) punteggio = domanda.PunteggioNoLimiti ?? 0;
          return {
            ...base,
            Risposte: [{ Testo: null, Punteggio: punteggio }]
          };
        }
        // Domanda testuale senza testo inserito e senza punteggio: nessuna risposta
        return base;
      })
    }))
  };

  delete moduloDaInviare.Titolo;
  delete moduloDaInviare.Descrizione;

  try {
    const res: Response = await $fetch('https://andrellaveloise.it/forms/users', {
      method: 'POST',
      body: {
        Token: token,
        Codice: formCode,
        ...moduloDaInviare,
      },
      onResponseError({ response }) {
        if (response.status === 404) {
          throw new Error(response._data.message);
        }
      },
    })
    toast.add({
      severity: 'success',
      summary: 'Modulo salvato',
      detail: 'Il modulo è stato salvato con successo.',
    });
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Errore',
      detail: (error as Error).message,
      life: 2500,
    });
  }

}

// Funzione per rimuovere una domanda
function rimuoviDomanda(sezioneIndex: number, domandaIndex: number) {
  modulo.value.Sezioni[sezioneIndex].Domande.splice(domandaIndex, 1);
}

// Funzione per rimuovere una sezione
function rimuoviSezione(sezioneIndex: number) {
  modulo.value.Sezioni.splice(sezioneIndex, 1);
}
</script>

<template>
  <div class="p-4">
    <div class="flex items-center gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4" style="border-color: #10b981;">
      <Button icon="pi pi-arrow-left" class="p-button-rounded p-button-secondary" as="router-link" to="/dashboard" />
      <Button icon="pi pi-eye" outlined class="p-button-rounded p-button-info" as="router-link"
        :to="`/view/${$route.params.code}`" />

      <div>
        <h1 class="text-2xl font-bold text-gray-800">Crea Modulo</h1>
        <p class="hidden md:block text-sm text-gray-500">Crea il tuo modulo personalizzato</p>
      </div>
    </div>

    <!-- Titolo e descrizione del modulo -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Titolo</label>
      <InputText v-model="modulo.Titolo" class="mt-1 block w-full" disabled />

      <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione</label>
      <Textarea v-model="modulo.Descrizione" class="mt-1 block w-full" auto-resize disabled />
    </div>

    <!-- Sezioni -->
    <div v-for="(sezione, sezioneIndex) in modulo.Sezioni" :key="sezioneIndex"
      class="mb-6 border border-gray-400 p-4 rounded">
      <div class="flex justify-between items-center mb-2">
        <label class="block text-sm font-medium text-gray-700">Nome Sezione</label>
        <Button icon="pi pi-trash" severity="danger" text rounded size="small" @click="rimuoviSezione(sezioneIndex)"
          label="Rimuovi sezione" class="ml-2 p-button-danger" />
      </div>
      <InputText v-model="sezione.Nome" class="mt-1 block w-full" />

      <!-- Domande -->
      <div v-for="(domanda, domandaIndex) in sezione.Domande" :key="domandaIndex"
        class="mt-4 border border-gray-400 p-4 rounded">
        <label class="block text-sm font-medium text-gray-700">Testo Domanda</label>
        <InputText v-model="domanda.Testo" class="mt-1 block w-full" />

        <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione Domanda</label>
        <Textarea v-model="domanda.Descrizione" class="mt-1 block w-full" auto-resize />

        <label class="block text-sm font-medium text-gray-700 mt-4">Tipologia Domanda</label>
        <Dropdown v-model.number="domanda.IDF_Tipologia" :options="Tipologie" optionLabel="Nome"
          optionValue="ID_Tipologia" placeholder="Seleziona tipologia" class="mt-1 block w-full"
          @change="aggiornaTipologiaDomanda(sezioneIndex, domandaIndex, domanda.IDF_Tipologia)" />

        <!-- Risposte -->
        <div v-if="domanda.IDF_Tipologia && (domanda.IDF_Tipologia === 4 || domanda.IDF_Tipologia === 5)"
          class="mt-4 flex flex-col gap-2">
          <div v-for="(risposta, rispostaIndex) in domanda.Risposte ?? []" :key="rispostaIndex"
            class="flex items-center gap-2">
            <InputText v-model="risposta.Testo" class="w-1/2" placeholder="Testo risposta" />
            <InputText :value="risposta.Punteggio?.toString() ?? ''" type="number" class="w-24"
              @input="e => risposta.Punteggio = Number((e.target as HTMLInputElement).value)" placeholder="Punteggio" />
            <Button v-if="domanda.IDF_Tipologia === 4 && domanda.Risposte && domanda.Risposte.length > 1"
              icon="pi pi-trash" severity="danger" text rounded size="small"
              @click="domanda.Risposte.splice(rispostaIndex, 1)" class="p-button-danger" />
          </div>
          <Button v-if="domanda.IDF_Tipologia === 4" @click="aggiungiRisposta(sezioneIndex, domandaIndex)"
            class="mt-2 w-fit p-button-success" outlined label="Aggiungi Risposta" icon="pi pi-plus" />

          <!-- ANTEPRIMA RISPOSTE -->
          <div class="mt-4 p-3 rounded border border-blue-300 bg-blue-50">
            <div class="font-semibold mb-2 text-blue-800">Anteprima risposte:</div>
            <ul class="list-disc pl-5">
              <li v-for="(risposta, idx) in domanda.Risposte ?? []" :key="'preview-' + idx">
                <span class="text-gray-800">{{ risposta.Testo }}</span>
                <span v-if="domanda.IDF_Tipologia === 4" class="text-xs text-gray-500 ml-2">(Punteggio: {{
                  risposta.Punteggio }})</span>
                <span v-if="domanda.IDF_Tipologia === 5" class="text-xs text-gray-500 ml-2">(Punteggio: {{
                  risposta.Punteggio }})</span>
              </li>
            </ul>
          </div>
          <!-- FINE ANTEPRIMA RISPOSTE -->
        </div>

        <!-- Risposta Breve -->
        <div v-else-if="domanda.IDF_Tipologia === 6" class="w-full mt-2 flex items-center gap-2">
          <InputText class="w-full" placeholder="Risposta breve" v-model="domanda.RispostaBreve" disabled />
          <InputText type="number" class="w-40" placeholder="Punteggio" v-model.number="domanda.PunteggioBreve" />
        </div>
        <!-- Risposta Lunga -->
        <div v-else-if="domanda.IDF_Tipologia === 7" class="w-full mt-2 flex items-center gap-2">
          <Textarea class="w-full" auto-resize placeholder="Risposta lunga" v-model="domanda.RispostaLunga" disabled />
          <InputText type="number" class="w-40" placeholder="Punteggio" v-model.number="domanda.PunteggioLunga" />
        </div>
        <!-- Risposta No Limiti -->
        <div v-else-if="domanda.IDF_Tipologia === 8" class="w-full mt-2 flex items-center gap-2">
          <Textarea class="w-full" auto-resize placeholder="Risposta senza limiti" v-model="domanda.RispostaNoLimiti"
            disabled />
          <InputText type="number" class="w-40" placeholder="Punteggio" v-model.number="domanda.PunteggioNoLimiti" />
        </div>

        <Button @click="rimuoviDomanda(sezioneIndex, domandaIndex)" class="mt-4  p-button-danger"
          label="Elimina Domanda" icon="pi pi-trash" outlined />
      </div>

      <Button @click="aggiungiDomanda(sezioneIndex)" class="mt-4 p-button-success" outlined label="Aggiungi Domanda"
        icon="pi pi-plus" />
    </div>
    <div class="flex gap-4 mt-4">
      <Button @click="aggiungiSezione" label="Aggiungi Sezione" outlined icon="pi pi-plus" class="p-button-success" />
      <Button @click="inviaModulo" class="!bg-[#10b981]" label="Invia Modulo" icon="pi pi-send" />
    </div>
  </div>
</template>

<style></style>