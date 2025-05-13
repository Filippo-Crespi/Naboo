<template>
  <div class="p-4">
    <div class="flex items-center gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4" style="border-color: #10b981;">
      <Button icon="pi pi-arrow-left" class="p-button-rounded" as="router-link" to="/dashboard" />
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Compila Modulo</h1>
        <p class="text-sm text-gray-500">Rispondi alle domande e invia il modulo</p>
      </div>
    </div>
    <div class="mb-4">
      <h1 class="text-2xl font-bold">{{ modulo.titolo }}</h1>
      <h2 class="text-xl">{{ modulo.descrizione }}</h2>
    </div>
    <div>
      <div v-for="(sezione, sezioneIndex) in modulo.sezioni" :key="sezioneIndex"
        class="mb-6 border border-gray-400 p-4 rounded">
        <h3 class="text-lg font-semibold">{{ sezione.nome }}</h3>
        <div v-for="(domanda, domandaIndex) in sezione.domande" :key="domandaIndex"
          class="mt-4 border border-gray-400 p-4 rounded">
          <h4 class="text-md font-medium">Domanda: {{ domanda.testo }}</h4>
          <p class="text-gray-600">{{ domanda.descrizione }}</p>
          <!-- Vero/Falso o Scelta Singola -->
          <div v-if="domanda.tipologia === 1 && domanda.risposte" class="mt-2 flex flex-col gap-2">
            <div v-for="(risposta, rispostaIndex) in domanda.risposte" :key="rispostaIndex"
              class="flex items-center gap-2">
              <RadioButton :inputId="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`" :value="rispostaIndex"
                :name="`d${sezioneIndex}-${domandaIndex}`"
                v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}`]" />
              <label :for="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`">{{ risposta.testo }}</label>
            </div>
          </div>
          <!-- Risposta Breve -->
          <InputText v-else-if="domanda.tipologia === 2" class="w-full mt-2" placeholder="Risposta breve"
            v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}`]" />
          <!-- Risposta Lunga -->
          <Textarea v-else-if="domanda.tipologia === 3" class="w-full mt-2" auto-resize placeholder="Risposta lunga"
            v-model="risposteUtente[`${sezioneIndex}-${domandaIndex}`]" />
          <!-- Scelta Multipla -->
          <div v-else-if="domanda.tipologia === 4 && domanda.risposte" class="mt-2 flex flex-col gap-2">
            <div v-for="(risposta, rispostaIndex) in domanda.risposte" :key="rispostaIndex"
              class="flex items-center gap-2">
              <Checkbox :inputId="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`" :value="rispostaIndex"
                :name="`d${sezioneIndex}-${domandaIndex}`"
                :model-value="risposteUtente[`${sezioneIndex}-${domandaIndex}`] || []"
                @update:model-value="val => selezionaRisposta(sezioneIndex, domandaIndex, rispostaIndex, true)" />
              <label :for="`d${sezioneIndex}-${domandaIndex}-${rispostaIndex}`">{{ risposta.testo }}</label>
            </div>
          </div>
        </div>
      </div>
      <div class="flex gap-4 mt-4">
        <Button label="Invia Modulo" icon="pi pi-send" class="p-button-success" @click="inviaModulo" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
// Modulo predefinito (come in anteprima)
const modulo = ref({
  titolo: "Modulo di Valutazione - Informatica Generale",
  descrizione: "Questo modulo copre concetti fondamentali di informatica, algoritmi, strutture dati, basi di programmazione e logica.",
  sezioni: [
    {
      nome: "Logica e Algoritmi",
      domande: [
        {
          testo: "Un algoritmo è una sequenza finita di istruzioni non ambigue.",
          descrizione: "Valuta se la definizione di algoritmo è corretta.",
          tipologia: 1,
          risposte: [
            { testo: "Vero", punteggio: 1 },
            { testo: "Falso", punteggio: 0 }
          ]
        },
        {
          testo: "Spiega cos’è un diagramma di flusso e a cosa serve.",
          descrizione: "Risposta aperta",
          tipologia: 3
        },
        {
          testo: "Quali di questi sono strutture di controllo?",
          descrizione: "Seleziona tutte le risposte corrette.",
          tipologia: 4,
          risposte: [
            { testo: "if", punteggio: 1 },
            { testo: "while", punteggio: 1 },
            { testo: "echo", punteggio: 0 },
            { testo: "for", punteggio: 1 }
          ]
        }
      ]
    }
  ]
});

// Oggetto che raccoglie le risposte dell'utente
const risposteUtente = ref<any>({});

// Funzione per gestire la selezione di una risposta (scelta singola o multipla)
function selezionaRisposta(sezioneIdx: number, domandaIdx: number, rispostaIdx: number, multipla = false) {
  const key = `${sezioneIdx}-${domandaIdx}`;
  if (multipla) {
    if (!Array.isArray(risposteUtente.value[key])) risposteUtente.value[key] = [];
    const arr = risposteUtente.value[key];
    const i = arr.indexOf(rispostaIdx);
    if (i === -1) arr.push(rispostaIdx);
    else arr.splice(i, 1);
  } else {
    risposteUtente.value[key] = rispostaIdx;
  }
}

// Funzione per gestire risposte aperte
function aggiornaRispostaAperta(sezioneIdx: number, domandaIdx: number, val: string) {
  const key = `${sezioneIdx}-${domandaIdx}`;
  risposteUtente.value[key] = val;
}

function inviaModulo() {
  // Qui puoi inviare risposteUtente.value al backend
  console.log('Risposte utente:', JSON.stringify(risposteUtente.value, null, 2));
}
</script>

<style></style>