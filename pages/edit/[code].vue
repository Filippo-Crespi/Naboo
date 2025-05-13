<script lang="ts" setup>
definePageMeta({
  middleware: ["auth"],
});

const route = useRoute();

const postId = route.params.code;

// Modulo iniziale vuoto
type Risposta = { Testo: string; Punteggio: number };
type Domanda = {
  Testo: string;
  Descrizione: string;
  Tipologia: number | null;
  Risposte?: Risposta[];
  RispostaAperta?: string;
  RispostaBreve?: string;
};

type Sezione = {
  Nome: string;
  Domande: Domanda[];
};

const modulo = ref<{ titolo: string; descrizione: string; sezioni: Sezione[] }>({
  titolo: '',
  descrizione: '',
  sezioni: [
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
  modulo.value.sezioni[sezioneIndex].Domande.push({
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
  const domanda = modulo.value.sezioni[sezioneIndex].Domande[domandaIndex];

  if (domanda.Tipologia === 4) { // Scelta Multipla
    if (!domanda.Risposte) domanda.Risposte = [];
    domanda.Risposte.push({ Testo: '', Punteggio: 0 });
  } else {
    console.warn('Le risposte multiple possono essere aggiunte solo per domande di tipo Scelta Multipla.');
  }
}

// Funzione per aggiornare la tipologia di una domanda e resettare le risposte
function aggiornaTipologiaDomanda(sezioneIndex: number, domandaIndex: number, nuovaTipologia: number) {
  const domanda = modulo.value.sezioni[sezioneIndex].Domande[domandaIndex];

  // Aggiorna la tipologia della domanda
  domanda.Tipologia = nuovaTipologia;

  // Resetta le risposte in base alla nuova tipologia
  if (nuovaTipologia === 4) { // Scelta Multipla
    domanda.Risposte = [{ Testo: '', Punteggio: 0 }];
    delete domanda.RispostaAperta;
    delete domanda.RispostaBreve;
  } else if (nuovaTipologia === 1) { // Vero/Falso
    domanda.Risposte = [
      { Testo: 'Vero', Punteggio: 1 },
      { Testo: 'Falso', Punteggio: 0 },
    ];
    delete domanda.RispostaAperta;
    delete domanda.RispostaBreve;
  } else if (nuovaTipologia === 2) { // Risposta Breve
    domanda.Risposte = undefined;
    domanda.RispostaBreve = '';
    delete domanda.RispostaAperta;
  } else if (nuovaTipologia === 3) { // Risposta Lunga
    domanda.Risposte = undefined;
    domanda.RispostaAperta = '';
    delete domanda.RispostaBreve;
  }
}

// Funzione per inviare il modulo al backend
function inviaModulo() {
  console.log(JSON.stringify(modulo.value));
}

// Funzione per rimuovere una domanda
function rimuoviDomanda(sezioneIndex: number, domandaIndex: number) {
  modulo.value.sezioni[sezioneIndex].Domande.splice(domandaIndex, 1);
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
      <InputText v-model="sezione.Nome" class="mt-1 block w-full" />

      <!-- Domande -->
      <div v-for="(domanda, domandaIndex) in sezione.Domande" :key="domandaIndex"
        class="mt-4 border border-gray-400 p-4 rounded">
        <label class="block text-sm font-medium text-gray-700">Testo Domanda</label>
        <InputText v-model="domanda.Testo" class="mt-1 block w-full" />

        <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione Domanda</label>
        <Textarea v-model="domanda.Descrizione" class="mt-1 block w-full" auto-resize />

        <label class="block text-sm font-medium text-gray-700 mt-4">Tipologia Domanda</label>
        <Dropdown v-model="domanda.Tipologia" :options="tipologie" optionLabel="nome" optionValue="id"
          placeholder="Seleziona tipologia" class="mt-1 block w-full"
          @change="aggiornaTipologiaDomanda(sezioneIndex, domandaIndex, domanda.Tipologia ?? 1)" />

        <!-- Risposte -->
        <div v-if="domanda.Tipologia && domanda.Risposte && domanda.Tipologia !== 3 && domanda.Tipologia !== 2"
          class="mt-4 flex flex-col gap-2">
          <div v-for="(risposta, rispostaIndex) in domanda.Risposte" :key="rispostaIndex"
            class="flex items-center gap-2">
            <RadioButton :inputId="`domanda-${domandaIndex}-risposta-${rispostaIndex}`" :value="risposta.Punteggio"
              :name="`domanda-${domandaIndex}`" disabled />
            <InputText v-model="risposta.Testo" class="w-1/2" />
            <InputText :value="risposta.Punteggio.toString()" type="number" class="w-24"
              @input="e => risposta.Punteggio = Number((e.target as HTMLInputElement).value)" />
          </div>
        </div>

        <Button v-if="domanda.Tipologia === 4" @click="aggiungiRisposta(sezioneIndex, domandaIndex)" class="mt-4"
          label="Aggiungi Risposta" icon="pi pi-plus" />
        <Button @click="rimuoviDomanda(sezioneIndex, domandaIndex)" class="mt-4 ml-2 p-button-danger"
          label="Elimina Domanda" icon="pi pi-trash" />
      </div>

      <Button @click="aggiungiDomanda(sezioneIndex)" class="mt-4" label="Aggiungi Domanda" icon="pi pi-plus" />
    </div>
    <div class="flex gap-4 mt-4">
      <Button @click="aggiungiSezione" label="Aggiungi Sezione" icon="pi pi-plus" />
      <Button @click="inviaModulo" class="p-button-danger" label="Invia Modulo" icon="pi pi-send" />
    </div>
  </div>
</template>

<style></style>