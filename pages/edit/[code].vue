<script lang="ts" setup>
const route = useRoute();

const postId = route.params.code;

// Modulo iniziale vuoto
type Risposta = { testo: string; punteggio: number };
type Domanda = {
  testo: string;
  descrizione: string;
  tipologia: number | null;
  risposte?: Risposta[];
  rispostaAperta?: string;
  rispostaBreve?: string;
};

type Sezione = {
  nome: string;
  domande: Domanda[];
};

const modulo = ref<{ titolo: string; descrizione: string; sezioni: Sezione[] }>({
  titolo: '',
  descrizione: '',
  sezioni: [
    {
      nome: '',
      domande: [
        {
          testo: '',
          descrizione: '',
          tipologia: null,
          risposte: [
            {
              testo: '',
              punteggio: 0,
            },
          ],
        },
      ],
    },
  ],
});

// Tipologie temporanee prese dal database
const tipologie = ref([
  { id: 1, nome: 'Vero/Falso' },
  { id: 2, nome: 'Risposta Breve' },
  { id: 3, nome: 'Risposta Lunga' },
  { id: 4, nome: 'Scelta Multipla' },
]);

// Funzioni per aggiungere sezioni, domande e risposte
function aggiungiSezione() {
  modulo.value.sezioni.push({
    nome: '',
    domande: [
      {
        testo: '',
        descrizione: '',
        tipologia: null,
        risposte: [
          {
            testo: '',
            punteggio: 0,
          },
        ],
      },
    ],
  });
}

function aggiungiDomanda(sezioneIndex: number) {
  modulo.value.sezioni[sezioneIndex].domande.push({
    testo: '',
    descrizione: '',
    tipologia: null,
    risposte: [
      {
        testo: '',
        punteggio: 0,
      },
    ],
  });
}

function aggiungiRisposta(sezioneIndex: number, domandaIndex: number) {
  const domanda = modulo.value.sezioni[sezioneIndex].domande[domandaIndex];

  if (domanda.tipologia === 4) { // Scelta Multipla
    if (!domanda.risposte) domanda.risposte = [];
    domanda.risposte.push({ testo: '', punteggio: 0 });
  } else {
    console.warn('Le risposte multiple possono essere aggiunte solo per domande di tipo Scelta Multipla.');
  }
}

// Funzione per aggiornare la tipologia di una domanda e resettare le risposte
function aggiornaTipologiaDomanda(sezioneIndex: number, domandaIndex: number, nuovaTipologia: number) {
  const domanda = modulo.value.sezioni[sezioneIndex].domande[domandaIndex];

  // Aggiorna la tipologia della domanda
  domanda.tipologia = nuovaTipologia;

  // Resetta le risposte in base alla nuova tipologia
  if (nuovaTipologia === 4) { // Scelta Multipla
    domanda.risposte = [{ testo: '', punteggio: 0 }];
    delete domanda.rispostaAperta;
    delete domanda.rispostaBreve;
  } else if (nuovaTipologia === 1) { // Vero/Falso
    domanda.risposte = [
      { testo: 'Vero', punteggio: 1 },
      { testo: 'Falso', punteggio: 0 },
    ];
    delete domanda.rispostaAperta;
    delete domanda.rispostaBreve;
  } else if (nuovaTipologia === 2) { // Risposta Breve
    domanda.risposte = undefined;
    domanda.rispostaBreve = '';
    delete domanda.rispostaAperta;
  } else if (nuovaTipologia === 3) { // Risposta Lunga
    domanda.risposte = undefined;
    domanda.rispostaAperta = '';
    delete domanda.rispostaBreve;
  }
}

// Funzione per inviare il modulo al backend
function inviaModulo() {
  console.log(JSON.stringify(modulo.value));
}

// Funzione per rimuovere una domanda
function rimuoviDomanda(sezioneIndex: number, domandaIndex: number) {
  modulo.value.sezioni[sezioneIndex].domande.splice(domandaIndex, 1);
}
</script>

<template>
  <div class="p-4">
    <div class="flex items-center gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4" style="border-color: #10b981;">
      <Button icon="pi pi-arrow-left" class="p-button-rounded" as="router-link" to="/dashboard" />
      <Button icon="pi pi-eye" class="p-button-rounded" as="router-link" :to="`/view/${$route.params.code}`" />

      <div>
        <h1 class="text-2xl font-bold text-gray-800">Crea Modulo</h1>
        <p class="text-sm text-gray-500">Crea il tuo modulo personalizzato</p>
      </div>
    </div>

    <!-- Titolo e descrizione del modulo -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Titolo</label>
      <InputText v-model="modulo.titolo" class="mt-1 block w-full" />

      <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione</label>
      <Textarea v-model="modulo.descrizione" class="mt-1 block w-full" auto-resize />
    </div>

    <!-- Sezioni -->
    <div v-for="(sezione, sezioneIndex) in modulo.sezioni" :key="sezioneIndex"
      class="mb-6 border border-gray-400 p-4 rounded">
      <label class="block text-sm font-medium text-gray-700">Nome Sezione</label>
      <InputText v-model="sezione.nome" class="mt-1 block w-full" />

      <!-- Domande -->
      <div v-for="(domanda, domandaIndex) in sezione.domande" :key="domandaIndex"
        class="mt-4 border border-gray-400 p-4 rounded">
        <label class="block text-sm font-medium text-gray-700">Testo Domanda</label>
        <InputText v-model="domanda.testo" class="mt-1 block w-full" />

        <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione Domanda</label>
        <Textarea v-model="domanda.descrizione" class="mt-1 block w-full" auto-resize />

        <label class="block text-sm font-medium text-gray-700 mt-4">Tipologia Domanda</label>
        <Dropdown v-model="domanda.tipologia" :options="tipologie" optionLabel="nome" optionValue="id"
          placeholder="Seleziona tipologia" class="mt-1 block w-full"
          @change="aggiornaTipologiaDomanda(sezioneIndex, domandaIndex, domanda.tipologia ?? 1)" />

        <!-- Risposte -->
        <div v-if="domanda.tipologia && domanda.risposte && domanda.tipologia !== 3 && domanda.tipologia !== 2"
          class="mt-4 flex flex-col gap-2">
          <div v-for="(risposta, rispostaIndex) in domanda.risposte" :key="rispostaIndex"
            class="flex items-center gap-2">
            <RadioButton :inputId="`domanda-${domandaIndex}-risposta-${rispostaIndex}`" :value="risposta.punteggio"
              :name="`domanda-${domandaIndex}`" disabled />
            <InputText v-model="risposta.testo" class="w-1/2" />
            <InputText :value="risposta.punteggio.toString()" type="number" class="w-24"
              @input="e => risposta.punteggio = Number((e.target as HTMLInputElement).value)" />
          </div>
        </div>

        <Button v-if="domanda.tipologia === 4" @click="aggiungiRisposta(sezioneIndex, domandaIndex)" class="mt-4"
          label="Aggiungi Risposta" icon="pi pi-plus" />
        <Button @click="rimuoviDomanda(sezioneIndex, domandaIndex)" class="mt-4 ml-2 p-button-danger"
          label="Elimina Domanda" icon="pi pi-trash" />
      </div>

      <Button @click="aggiungiDomanda(sezioneIndex)" class="mt-4" label="Aggiungi Domanda" icon="pi pi-plus" />
    </div>

    <Button @click="aggiungiSezione" class="mt-4" label="Aggiungi Sezione" icon="pi pi-plus" />
    <Button @click="inviaModulo" class="mt-6 p-button-danger" label="Invia Modulo" icon="pi pi-send" />
  </div>
</template>

<style></style>