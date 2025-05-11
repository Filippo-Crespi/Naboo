<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const postId = route.params.code;

// Modulo iniziale vuoto
const modulo = ref({
  titolo: '',
  descrizione: '',
  sezioni: [
    {
      nome: '',
      domande: [
        {
          testo: '',
          descrizione: '',
          tipologia: null, // Tipologia selezionata
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
        tipologia: null, // Tipologia selezionata
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
    tipologia: null, // Tipologia selezionata
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
    domanda.risposte.push({ testo: '', punteggio: 0 });
  } else {
    console.warn('Le risposte multiple possono essere aggiunte solo per domande di tipo Scelta Multipla.');
  }
}

// Funzione per aggiornare la tipologia di una domanda e resettare le risposte
function aggiornaTipologiaDomanda(sezioneIndex: number, domandaIndex: number, nuovaTipologia: number) {
  const domanda = modulo.value.sezioni[sezioneIndex].domande[domandaIndex];

  // Aggiorna la tipologia della domanda
  // @ts-ignore
  domanda.tipologia = nuovaTipologia;

  // Resetta le risposte in base alla nuova tipologia
  if (nuovaTipologia === 4) { // Scelta Multipla
    domanda.risposte = [{ testo: '', punteggio: 0 }];
  } else if (nuovaTipologia === 1) { // Vero/Falso
    domanda.risposte = [
      { testo: 'Vero', punteggio: 1 },
      { testo: 'Falso', punteggio: 0 },
    ];
  } else if (nuovaTipologia === 2 || nuovaTipologia === 3) { // Risposta Breve o Risposta Lunga
    delete domanda.risposte; // Rimuove il campo risposte
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
    <h1 class="text-2xl font-bold mb-4">Crea Modulo</h1>

    <!-- Titolo e descrizione del modulo -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Titolo</label>
      <input v-model="modulo.titolo" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />

      <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione</label>
      <textarea v-model="modulo.descrizione" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
    </div>

    <!-- Sezioni -->
    <div v-for="(sezione, sezioneIndex) in modulo.sezioni" :key="sezioneIndex" class="mb-6 border p-4 rounded">
      <label class="block text-sm font-medium text-gray-700">Nome Sezione</label>
      <input v-model="sezione.nome" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />

      <!-- Domande -->
      <div v-for="(domanda, domandaIndex) in sezione.domande" :key="domandaIndex" class="mt-4 border p-4 rounded">
        <label class="block text-sm font-medium text-gray-700">Testo Domanda</label>
        <input v-model="domanda.testo" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />

        <label class="block text-sm font-medium text-gray-700 mt-4">Descrizione Domanda</label>
        <textarea v-model="domanda.descrizione"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>

        <label class="block text-sm font-medium text-gray-700 mt-4">Tipologia Domanda</label>
        <select v-model="domanda.tipologia"
          @change="aggiornaTipologiaDomanda(sezioneIndex, domandaIndex, domanda.tipologia)"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          <option v-for="tipologia in tipologie" :key="tipologia.id" :value="tipologia.id">
            {{ tipologia.nome }}
          </option>
        </select>

        <!-- Risposte -->
        <div v-if="domanda.risposte" v-for="(risposta, rispostaIndex) in domanda.risposte" :key="rispostaIndex"
          class="mt-4">
          <label class="block text-sm font-medium text-gray-700">Testo Risposta</label>
          <input v-model="risposta.testo" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />

          <label class="block text-sm font-medium text-gray-700 mt-2">Punteggio</label>
          <input v-model.number="risposta.punteggio" type="number"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>

        <!-- Opzioni Vero/Falso -->
        <template v-if="domanda.tipologia === 1">
          <ul>
            <li>Vero (Punteggio: {{ domanda.risposte[0]?.punteggio }})</li>
            <li>Falso (Punteggio: {{ domanda.risposte[1]?.punteggio }})</li>
          </ul>
        </template>

        <button v-if="domanda.tipologia === 4" @click="aggiungiRisposta(sezioneIndex, domandaIndex)"
          class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
          Aggiungi Risposta
        </button>

        <button @click="rimuoviDomanda(sezioneIndex, domandaIndex)"
          class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
          Elimina Domanda
        </button>
      </div>

      <button @click="aggiungiDomanda(sezioneIndex)"
        class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
        Aggiungi Domanda
      </button>
    </div>

    <button @click="aggiungiSezione" class="mt-4 px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
      Aggiungi Sezione
    </button>

    <button @click="inviaModulo" class="mt-6 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
      Invia Modulo
    </button>
  </div>
</template>

<style></style>