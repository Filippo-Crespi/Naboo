<template>
  <div class="p-4">
    <div class="flex items-center gap-3 mb-8 p-4 rounded-lg shadow bg-white border-b-4" style="border-color: #10b981;">
      <Button icon="pi pi-arrow-left" class="p-button-rounded" as="router-link" to="/dashboard" />
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Report Risposte Modulo</h1>
        <p class="text-sm text-gray-500">Riepilogo di tutte le risposte ricevute</p>
      </div>
    </div>
    <div class="mb-4">
      <h1 class="text-2xl font-bold">Modulo di esempio</h1>
      <h2 class="text-xl">Descrizione del modulo di esempio</h2>
    </div>
    <div class="space-y-8">
      <div v-for="(compilazione, idx) in compilazioni" :key="idx"
        class="border border-gray-300 rounded-lg p-4 bg-white shadow">
        <div class="mb-2 flex items-center gap-4">
          <span class="font-semibold text-[#10b981]">Compilazione #{{ idx + 1 }}</span>
          <span class="text-xs text-gray-500">Utente: {{ compilazione.utente }}</span>
          <span class="text-xs text-gray-400">Data: {{ compilazione.data }}</span>
        </div>
        <div v-for="(risposta, rIdx) in compilazione.risposte" :key="rIdx" class="mb-2">
          <div class="font-medium">{{ risposta.domanda }}</div>
          <div class="ml-2">
            <span v-if="Array.isArray(risposta.risposta)">
              <span v-for="(val, i) in risposta.risposta" :key="i"
                class="inline-block bg-[#10b981]/10 text-[#10b981] rounded px-2 py-1 mr-2">{{ val }}</span>
            </span>
            <span v-else>{{ risposta.risposta }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
definePageMeta({
  middleware: ["auth"],
});
// Dati fittizi per la struttura del report
const compilazioni = [
  {
    utente: "Mario Rossi",
    data: "2025-05-13 10:15",
    risposte: [
      { domanda: "Un algoritmo è una sequenza finita di istruzioni non ambigue.", risposta: "Vero" },
      { domanda: "Spiega cos’è un diagramma di flusso e a cosa serve.", risposta: "Serve a rappresentare graficamente un algoritmo." },
      { domanda: "Quali di questi sono strutture di controllo?", risposta: ["if", "while", "for"] },
    ]
  },
  {
    utente: "Giulia Bianchi",
    data: "2025-05-13 11:02",
    risposte: [
      { domanda: "Un algoritmo è una sequenza finita di istruzioni non ambigue.", risposta: "Falso" },
      { domanda: "Spiega cos’è un diagramma di flusso e a cosa serve.", risposta: "Per visualizzare i passi di un processo." },
      { domanda: "Quali di questi sono strutture di controllo?", risposta: ["if", "for"] },
    ]
  }
];
</script>

<style></style>