<script lang="ts" setup>
import { type Form, type Response, type Section } from '~/types';


definePageMeta({
  middleware: ["auth"],
});

const route = useRoute();
const session_uuid = useCookie('session_uuid').value
const toast = useToast();

const formCode = route.params.code;

const types = [
  { id_type: 1, text: 'Autenticazione' },
  { id_type: 4, text: 'Risposta Multipla' },
  { id_type: 5, text: 'Vero Falso' },
  { id_type: 6, text: 'Risposta breve' },
  { id_type: 7, text: 'Risposta lunga' },
  { id_type: 8, text: 'Risposta senza limiti' },
];

const modulo = ref<Form>({
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
            options: [],
          },
        ],
      },
    ],
  },
});


try {
  const res: Response = await $fetch('http://localhost:8085/api/moduli/modulo.php?code=' + formCode, {
    method: 'get',
    onResponseError({ response }) {
      if (response.status === 404) {
        modulo.value = {
          title: response._data.data?.title || '',
          description: response._data.data?.description || '',
          data: {
            sections: [
              {
                title: '',
                questions: [
                  {
                    label: '',
                    description: '',
                    type: '4',
                    options: [
                      {
                        label: '',
                        score: 0,
                      },
                    ],
                  },
                ],
              },
            ],
          }
        }
        throw new Error('Modulo non trovato, creato oggetto vuoto con titolo e descrizione.');
      }
    }
  })
  // Assegna title e description se presenti nella struttura dati
  modulo.value = {
    ...res.data,
    title: res.data.data?.title ?? res.data.title ?? '',
    description: res.data.data?.description ?? res.data.description ?? '',
  }
} catch (error) {
  // Se modulo.value non è già stato impostato dal 404, fallback generico
  toast.add({
    severity: 'error',
    summary: 'Errore',
    detail: (error as Error).message || "Impossibile caricare il modulo.",
    life: 2500,
  })
}

// Dopo il caricamento del modulo, assicura che le risposte siano coerenti con la tipologia
if (modulo.value && Array.isArray(modulo.value.data?.sections)) {
  // Itera su ogni sezione e domanda per aggiornare le tipologie
  modulo.value.data?.sections.forEach((sezione, sezioneIndex) => {
    if (Array.isArray(sezione.questions)) {
      sezione.questions.forEach((domanda, domandaIndex) => {
        if (domanda.type) {
          aggiornaTipologiaDomanda(sezioneIndex, domandaIndex, domanda.type);
        }
      });
    }
  });
}

// Funzioni per aggiungere sezioni, domande e risposte

// aggiungi risposta
function aggiungiRisposta(sezioneIndex: number, domandaIndex: number) {
  const domanda = modulo.value.data?.sections[sezioneIndex]?.questions[domandaIndex];
  if (!domanda) return;
  if (!Array.isArray(domanda.options)) domanda.options = [];
  domanda.options.push({ label: '', score: 0 });
}

function aggiungiSezione() {
  modulo.value.data?.sections.push({
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
  });
}

function aggiungiDomanda(sezioneIndex: number) {
  modulo.value.data?.sections[sezioneIndex].questions.push({
    label: '',
    description: '',
    type: 4,
    options: [
      { label: '', score: 0 },
    ],
  });
}

function aggiornaTipologiaDomanda(sezioneIndex: number, domandaIndex: number, nuovaTipologia: number | string) {
  const typeNum = typeof nuovaTipologia === 'string' ? parseInt(nuovaTipologia) : nuovaTipologia;
  const domanda = modulo.value.data?.sections[sezioneIndex]?.questions[domandaIndex];
  if (!domanda) return;
  domanda.type = typeNum;
  if (typeNum === 4) {
    if (!Array.isArray(domanda.options) || domanda.options.length === 0) {
      domanda.options = [{ label: '', score: 0 }];
    }
  } else if (typeNum === 5) {
    domanda.options = [
      { label: 'Vero', score: 1 },
      { label: 'Falso', score: 0 },
    ];
  } else if (typeNum === 6 || typeNum === 7 || typeNum === 8) {
    // Se già esiste una opzione, mantieni label e score
    if (!Array.isArray(domanda.options) || domanda.options.length === 0) {
      domanda.options = [{ label: '', score: 0 }];
    } else if (domanda.options.length > 1) {
      // Se per errore ci sono più opzioni, tieni solo la prima
      domanda.options = [domanda.options[0]];
    }
    // Altrimenti lascia invariato label e score
  } else {
    domanda.options = [];
  }
}

async function inviaModulo() {
  const moduloDaInviare = {
    sections: modulo.value.data?.sections.map((sezione: Section) => ({
      title: sezione.title,
      questions: sezione.questions.map((domanda) => {
        const base = {
          label: domanda.label,
          description: domanda.description,
          type: domanda.type
        };
        // Tutte le risposte sono in options, anche per domande testuali
        return {
          ...base,
          options: (domanda.options || []).map((r) => ({
            label: r.label,
            score: r.score
          }))
        };
      })
    }))
  };
  try {
    const res: Response = await $fetch('http://localhost:8085/api/moduli/utenti.php', {
      method: 'POST',
      body: {
        session_uuid,
        code: formCode,
        data: moduloDaInviare,
      },
      onResponseError({ response }) {
        if (response.status === 404) {
          throw new Error(response._data?.message || "Modulo non trovato. Ricarica la pagina.");
        }
        if (response.status === 400) {
          throw new Error('Errore di validazione: ' + (response._data?.message || "Controlla i dati inseriti."));
        }
        throw new Error("Errore imprevisto. Riprova più tardi.");
      },
    })
    toast.add({
      severity: 'success',
      summary: 'Modulo salvato',
      detail: 'Il modulo è stato salvato con successo.',
      life: 2500,
    });
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Errore',
      detail: (error as Error).message || "Salvataggio non riuscito. Riprova o contatta l'assistenza.",
      life: 2500,
    });
  }
}

// Funzione per rimuovere una domanda
function rimuoviDomanda(sezioneIndex: number, domandaIndex: number) {
  modulo.value.data?.sections[sezioneIndex]?.questions.splice(domandaIndex, 1);
}

// Funzione per rimuovere una sezione
function rimuoviSezione(sezioneIndex: number) {
  modulo.value.data?.sections.splice(sezioneIndex, 1);
}

// Funzione per rimuovere una risposta
function rimuoviRisposta(sezioneIndex: number, domandaIndex: number, rispostaIndex: number) {
  const domanda = modulo.value.data?.sections[sezioneIndex]?.questions[domandaIndex];
  if (domanda && Array.isArray(domanda.options)) {
    domanda.options.splice(rispostaIndex, 1);
  }
}
</script>

<template>
  <Toast />
  <div class="p-4">
    <div class="flex items-center gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4" style="border-color: #10b981;">
      <Button icon="pi pi-arrow-left" class="p-button-rounded p-button-secondary" as="router-link" to="/dashboard">
        <Icon name="solar:arrow-left-bold-duotone" />
      </Button>
      <Button icon="pi pi-eye" outlined class="p-button-rounded p-button-info" as="router-link"
        :to="`/dashboard/view/${$route.params.code}`">
        <Icon name="solar:eye-bold-duotone" />
      </Button>

      <div>
        <h1 class="text-2xl font-bold text-gray-800">Crea Modulo</h1>
        <p class="hidden md:block text-sm text-gray-500">Crea il tuo modulo personalizzato</p>
      </div>
    </div>

    <!-- Titolo e descrizione del modulo -->
    <!-- <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Titolo</label>
      <InputText v-model="modulo?.title" class="mt-1 block w-full" disabled />

      <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione</label>
      <Textarea v-model="modulo?.description" class="mt-1 block w-full" auto-resize disabled />
    </div> -->

    <!-- Sezioni -->
    <div v-for="(sezione, sezioneIndex) in modulo.data?.sections" :key="sezioneIndex"
      class="mb-6 border border-gray-400 p-4 rounded">
      <div class="flex justify-between items-center mb-2">
        <label class="block text-sm font-medium text-gray-700">Nome Sezione</label>
        <Button severity="danger" text @click="rimuoviSezione(sezioneIndex)" class="ml-2">
          <Icon name="solar:trash-bin-2-bold-duotone" />
          <span class="font-semibold">Rimuovi sezione</span>
        </Button>
      </div>
      <InputText v-model="sezione.title" class="mt-1 block w-full" />

      <!-- Domande -->
      <div v-for="(domanda, domandaIndex) in sezione.questions" :key="domandaIndex"
        class="mt-4 border border-gray-400 p-4 rounded">
        <label class="block text-sm font-medium text-gray-700">Testo Domanda</label>
        <InputText v-model="domanda.label" class="mt-1 block w-full" />

        <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione Domanda</label>
        <Textarea v-model="domanda.description" class="mt-1 block w-full" auto-resize />

        <label class="block text-sm font-medium text-gray-700 mt-4">Tipologia Domanda</label>
        <Select v-model.number="domanda.type" :options="types" optionLabel="text" optionValue="id_type"
          placeholder="Seleziona tipologia" class="mt-1 block w-full"
          @change="aggiornaTipologiaDomanda(sezioneIndex, domandaIndex, domanda.type)" />

        <!-- Risposte -->
        <div v-if="domanda.type && (domanda.type === 4 || domanda.type === 5)" class="mt-4 flex flex-col gap-2">
          <div v-for="(risposta, rispostaIndex) in domanda.options ?? []" :key="rispostaIndex"
            class="flex items-center gap-2">
            <InputText v-model="risposta.label" class="w-1/2" placeholder="Testo risposta" />
            <InputText :value="risposta.score?.toString() ?? ''" type="number" class="w-24"
              @input="e => risposta.score = Number((e.target as HTMLInputElement).value)" placeholder="Punteggio" />
            <Button v-if="domanda.options && domanda.options.length > 1" severity="danger" text rounded size="small"
              @click="rimuoviRisposta(sezioneIndex, domandaIndex, rispostaIndex)" class="p-button-danger">
              <Icon name="solar:trash-bin-2-bold-duotone" />
            </Button>
          </div>
          <Button v-if="domanda.type === 4" @click="aggiungiRisposta(sezioneIndex, domandaIndex)"
            class="mt-2 w-fit p-button-success" outlined label="Aggiungi Risposta" />

          <!-- ANTEPRIMA RISPOSTE -->
          <div class="mt-4 p-3 rounded border border-blue-300 bg-blue-50">
            <div class="font-semibold mb-2 text-blue-800">Anteprima risposte:</div>
            <ul class="list-disc pl-5">
              <li v-for="(risposta, idx) in domanda.options ?? []" :key="'preview-' + idx">
                <span class="text-gray-800">{{ risposta.label }}</span>
                <span v-if="domanda.type === 4" class="text-xs text-gray-500 ml-2">(Punteggio: {{
                  risposta.score }})</span>
                <span v-if="domanda.type === 5" class="text-xs text-gray-500 ml-2">(Punteggio: {{
                  risposta.score }})</span>
              </li>
            </ul>
          </div>
          <!-- FINE ANTEPRIMA RISPOSTE -->
        </div>

        <!-- Risposta Breve -->
        <div v-else-if="domanda.type === 6" class="w-full mt-2 flex items-center gap-2">
          <InputText disabled class="w-full" placeholder="Risposta breve" :value="domanda.options?.[0]?.label ?? ''"
            @input="e => domanda.options && (domanda.options[0].label = (e.target as HTMLInputElement).value)" />
          <InputText type="number" class="w-40" placeholder="Punteggio"
            :value="String(domanda.options?.[0]?.score ?? '')"
            @input="e => domanda.options && (domanda.options[0].score = Number((e.target as HTMLInputElement).value))" />
        </div>
        <!-- Risposta Lunga -->
        <div v-else-if="domanda.type === 7" class="w-full mt-2 flex items-center gap-2">
          <Textarea disabled class="w-full" auto-resize placeholder="Risposta lunga"
            :modelValue="domanda.options?.[0]?.label ?? ''"
            @update:modelValue="val => domanda.options && (domanda.options[0].label = val)" />
          <InputText type="number" class="w-40" placeholder="Punteggio"
            :value="String(domanda.options?.[0]?.score ?? '')"
            @input="e => domanda.options && (domanda.options[0].score = Number((e.target as HTMLInputElement).value))" />
        </div>
        <!-- Risposta No Limiti -->
        <div v-else-if="domanda.type === 8" class="w-full mt-2 flex items-center gap-2">
          <Textarea disabled class="w-full" auto-resize placeholder="Risposta senza limiti"
            :modelValue="domanda.options?.[0]?.label ?? ''"
            @update:modelValue="val => domanda.options && (domanda.options[0].label = val)" />
          <InputText type="number" class="w-40" placeholder="Punteggio"
            :value="String(domanda.options?.[0]?.score ?? '')"
            @input="e => domanda.options && (domanda.options[0].score = Number((e.target as HTMLInputElement).value))" />
        </div>

        <Button @click="rimuoviDomanda(sezioneIndex, domandaIndex)" class="mt-4  p-button-danger" outlined>
          <Icon name="solar:trash-bin-2-bold-duotone" />
          <span class="font-semibold">Rimuovi Domanda</span>
        </Button>
      </div>

      <Button @click="aggiungiDomanda(sezioneIndex)" class="mt-4 p-button-success" outlined>
        <span class="font-semibold">Aggiungi domanda</span>
      </Button>
    </div>
    <div class="flex gap-4 mt-4">
      <Button @click="aggiungiSezione" label="Aggiungi Sezione" outlined class="p-button-success" />
      <Button @click="inviaModulo" class="!bg-[#10b981]" label="Invia Modulo" />
    </div>
  </div>
</template>

<style></style>