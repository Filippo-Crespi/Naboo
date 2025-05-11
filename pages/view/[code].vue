<script lang="ts" setup>
import { ref } from 'vue';

// Modulo iniziale vuoto
const modulo = ref({
  titolo: 'Titolo di esempio',
  descrizione: 'Descrizione di esempio',
  sezioni: [
    {
      nome: 'Sezione 1',
      domande: [
        {
          testo: 'Domanda 1',
          descrizione: 'Descrizione della domanda 1',
          tipologia: 1, // Tipologia selezionata
          risposte: [
            { testo: 'Vero', punteggio: 1 },
            { testo: 'Falso', punteggio: 0 },
          ],
        },
        {
          testo: 'Domanda 2',
          descrizione: 'Descrizione della domanda 2',
          tipologia: 4, // Scelta Multipla
          risposte: [
            { testo: 'Opzione 1', punteggio: 5 },
            { testo: 'Opzione 2', punteggio: 3 },
          ],
        },
        {
          testo: 'Domanda 3',
          descrizione: 'Descrizione della domanda 3',
          tipologia: 2, // Risposta Breve
          risposte: [],
        },
        {
          testo: 'Domanda 4',
          descrizione: 'Descrizione della domanda 4',
          tipologia: 3, // Risposta Lunga
          risposte: [],
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

// Funzione per ottenere il nome della tipologia
function getTipologiaNome(id: number | null): string {
  const tipologia = tipologie.value.find((t) => t.id === id);
  return tipologia ? tipologia.nome : 'Sconosciuta';
}
</script>

<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Visualizza Modulo</h1>

    <!-- Titolo e descrizione del modulo -->
    <div class="mb-4">
      <h2 class="text-lg font-semibold">Titolo</h2>
      <p class="text-gray-700">{{ modulo.titolo }}</p>

      <h2 class="text-lg font-semibold mt-4">Descrizione</h2>
      <p class="text-gray-700">{{ modulo.descrizione }}</p>
    </div>

    <!-- Sezioni -->
    <div v-for="(sezione, sezioneIndex) in modulo.sezioni" :key="sezioneIndex" class="mb-6 border p-4 rounded">
      <h3 class="text-lg font-semibold">Sezione: {{ sezione.nome }}</h3>

      <!-- Domande -->
      <div v-for="(domanda, domandaIndex) in sezione.domande" :key="domandaIndex" class="mt-4 border p-4 rounded">
        <h4 class="text-md font-medium">Domanda: {{ domanda.testo }}</h4>
        <p class="text-gray-600">{{ domanda.descrizione }}</p>

        <h5 class="text-sm font-medium mt-2">Tipologia: {{ getTipologiaNome(domanda.tipologia) }}</h5>

        <!-- Risposte -->
        <ul class="list-disc pl-5 mt-2">
          <li v-for="(risposta, rispostaIndex) in domanda.risposte" :key="rispostaIndex">
            <template v-if="domanda.tipologia === 4">
              {{ risposta.testo }} (Punteggio: {{ risposta.punteggio }})
            </template>
            <template v-else-if="domanda.tipologia === 2 || domanda.tipologia === 3">
              <!-- Nascondi input del testo per Risposta Breve o Lunga -->
              Risposta aperta (nessun testo predefinito)
            </template>
            <template v-if="domanda.tipologia === 1">
              <ul>
                <li>Vero (Punteggio: {{ domanda.risposte[0]?.punteggio }})</li>
                <li>Falso (Punteggio: {{ domanda.risposte[1]?.punteggio }})</li>
              </ul>
            </template>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style></style>