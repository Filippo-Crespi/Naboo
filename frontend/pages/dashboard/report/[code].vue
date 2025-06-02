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
      <h1 class="text-2xl font-bold">Modulo - <span class="font-semibold text-[#10b981]">{{ code }}</span></h1>
    </div>
    <div v-if="loading" class="flex flex-col items-center justify-center text-lg min-h-[200px]">
      <svg class="animate-spin h-8 w-8 text-[#10b981] mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
      </svg>
      Caricamento...
    </div>
    <template v-else>
      <div class="mb-4 flex flex-col md:flex-row gap-4 items-center">
        <label class="font-medium">Visualizzazione:</label>
        <Select v-model="groupByPerson" :options="viewOptions" optionLabel="label" optionValue="value" class="w-72" />
      </div>
      <div v-if="!groupByPerson">
        <!-- Visualizzazione classica per domanda -->
        <div class="space-y-8">
          <div v-for="(domanda, idx) in domande" :key="domanda.ID_Domanda"
            class="border border-gray-300 rounded-lg p-4 bg-white shadow">
            <div class="mb-2 flex items-center gap-4">
              <span class="font-semibold text-[#10b981]">Domanda {{ idx + 1 }}</span>
              <span class="text-xs text-gray-500">Risposte totali: {{ domanda.Numero_Risposte }}</span>
            </div>
            <div class="font-medium mb-2">{{ domanda.Testo }}</div>
            <div class="font-light mb-2">{{ domanda.Descrizione }}</div>
            <div v-if="domanda.Risposte.length > 0">
              <!-- Tabella dinamica in base al tipo di risposta -->
              <DataTable v-if="domanda.Risposte.some((r: any) => r.RispostaBreve)" :value="domanda.Risposte"
                class="mb-2" size="small" stripedRows>
                <Column field="RispostaBreve" header="Risposta Breve"
                  :body="(r: any) => r.RispostaBreve || 'Senza risposta'" />
                <Column field="Punteggio" header="Punteggio"
                  :body="(r: any) => r.Punteggio !== undefined && r.Punteggio !== null && r.Punteggio !== '' ? Number(r.Punteggio) : 0"
                  style="width: 100px" />
              </DataTable>
              <DataTable v-else-if="domanda.Risposte.some((r: any) => r.RispostaLunga)" :value="domanda.Risposte"
                class="mb-2" size="small" stripedRows>
                <Column field="RispostaLunga" header="Risposta Lunga"
                  :body="(r: any) => r.RispostaLunga || 'Senza risposta'" />
                <Column field="Punteggio" header="Punteggio"
                  :body="(r: any) => r.Punteggio !== undefined && r.Punteggio !== null && r.Punteggio !== '' ? Number(r.Punteggio) : 0"
                  style="width: 100px" />
              </DataTable>
              <DataTable v-else-if="domanda.Risposte.some((r: any) => r.RispostaNoLimiti)" :value="domanda.Risposte"
                class="mb-2" size="small" stripedRows>
                <Column field="RispostaNoLimiti" header="Risposta No Limiti"
                  :body="(r: any) => r.RispostaNoLimiti || 'Senza risposta'" />
                <Column field="Punteggio" header="Punteggio"
                  :body="(r: any) => r.Punteggio !== undefined && r.Punteggio !== null && r.Punteggio !== '' ? Number(r.Punteggio) : 0"
                  style="width: 100px" />
              </DataTable>
              <DataTable v-else :value="domanda.Risposte" class="mb-2" size="small" stripedRows>
                <Column field="Testo" header="Testo" :body="(r: any) => r.Testo || 'Senza risposta'" />
                <Column field="Punteggio" header="Punteggio"
                  :body="(r: any) => r.Punteggio !== undefined && r.Punteggio !== null && r.Punteggio !== '' ? Number(r.Punteggio) : 0"
                  style="width: 100px" />
              </DataTable>
            </div>
            <div v-else class="text-gray-400 italic">Nessuna risposta registrata</div>
          </div>
        </div>
      </div>
      <div v-else>
        <!-- Visualizzazione raggruppata per compilatore -->
        <div v-if="groupedByResult.length > 0" class="mb-8">
          <div class="flex items-center gap-4 mb-2">
            <Button size="small" :label="showChart ? 'Nascondi grafico' : 'Mostra grafico'" icon="pi pi-chart-line"
              @click="showChart = !showChart" />
          </div>
          <client-only>
            <div style="max-width: 70vw; margin: 0 auto;">
              <Line v-if="showChart" :data="chartData" :options="chartOptions" height="180" />
            </div>
          </client-only>
        </div>
        <div v-if="groupedByResult.length === 0" class="text-gray-400 italic">Nessuna risposta registrata</div>
        <div v-else class="space-y-8">
          <div v-for="(compilazione, idx) in groupedByResult" :key="compilazione.ID_Risultato"
            class="border border-gray-300 rounded-lg p-4 bg-white shadow">
            <div class="mb-2 flex items-center gap-4">
              <span class="font-semibold text-[#10b981]">Compilatore #{{ idx + 1 }}</span>
              <span class="text-xs text-gray-500">Punteggio totale: {{ getPunteggioTotale(compilazione) }}</span>
            </div>
            <div>
              <DataTable :value="compilazione.risposte" class="mb-2" size="small" stripedRows>
                <Column field="domanda" header="Domanda"
                  :body="(row: any) => h('div', { class: 'block md:inline' }, row.domanda)" :style="{ width: 'auto' }"
                  :class="'!p-2'" />
                <Column field="risposta" header="Risposta"
                  :body="(row: any) => h('div', { class: 'block md:inline font-semibold' }, row.risposta)"
                  :style="{ width: 'auto' }" :class="'!p-2'" />
              </DataTable>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script lang="ts" setup>
definePageMeta({
  middleware: ['auth']
});

const route = useRoute();
const code = route.params.code as string;
const loading = ref(true);
const domande = ref<any[]>([]);
const idModulo = ref<string>('');
const groupByPerson = ref(true);
const viewOptions = [
  { label: 'Per domanda', value: false },
  { label: 'Raggruppata per compilatore', value: true }
];

onMounted(async () => {
  try {
    const res: any = await $fetch(`https://andrellaveloise.it/api/reports/reports.php?codice=${code}`);
    idModulo.value = res.ID_Modulo || '';
    domande.value = (res.Domande || res.domande || []).map((d: any) => ({
      ID_Domanda: String(d.ID_Domanda),
      Testo: d.Testo,
      Numero_Risposte: d.NPersone ?? d.Numero_Risposte ?? 0,
      Risposte: (d.Risposte || []).map((r: any) => ({
        IDF_Risposta: r.IDF_Risposta !== undefined ? (r.IDF_Risposta !== null ? String(r.IDF_Risposta) : null) : null,
        ID_Risposta: r.ID_Risposta !== undefined ? String(r.ID_Risposta) : '',
        ID_Risultato: r.ID_Risultato !== undefined ? String(r.ID_Risultato) : '',
        Testo: r.Testo ?? '',
        RispostaBreve: r.RispostaBreve ?? null,
        RispostaLunga: r.RispostaLunga ?? null,
        RispostaNoLimiti: r.RispostaNoLimiti ?? null,
        Punteggio: r.Punteggio !== undefined && r.Punteggio !== null && r.Punteggio !== '' ? Number(r.Punteggio) : 0,
      }))
    }));
  } catch (e) {
    domande.value = [];
  } finally {
    loading.value = false;
  }
});

const groupedByResult = computed(() => {
  // Raggruppa tutte le risposte per ID_Risultato
  const resultMap: Record<string, { ID_Risultato: string, risposte: { domanda: string, risposta: string, punteggio: number }[] }> = {};
  domande.value.forEach(domanda => {
    domanda.Risposte.forEach((r: any) => {
      if (!r.ID_Risultato) return;
      if (!resultMap[r.ID_Risultato]) {
        resultMap[r.ID_Risultato] = { ID_Risultato: r.ID_Risultato, risposte: [] };
      }
      let rispostaTesto = r.RispostaBreve ?? r.RispostaLunga ?? r.RispostaNoLimiti ?? r.Testo ?? '-';
      let punteggio = 0;
      if (r.Punteggio !== undefined && r.Punteggio !== null && r.Punteggio !== '') {
        punteggio = Number(r.Punteggio);
      }
      resultMap[r.ID_Risultato].risposte.push({ domanda: domanda.Testo, risposta: rispostaTesto, punteggio });
    });
  });
  return Object.values(resultMap);
});

function getPunteggioTotale(compilazione: { risposte: any[] }) {
  let totale = 0;
  compilazione.risposte.forEach(r => {
    totale += typeof r.punteggio === 'number' && !isNaN(r.punteggio) ? r.punteggio : 0;
  });
  return totale;
}

// Chart.js setup
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, LineElement, PointElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, LineElement, PointElement, CategoryScale, LinearScale);

const showChart = ref(false);
const chartData = ref<any>({
  labels: [],
  datasets: [
    {
      label: 'Punteggio totale',
      backgroundColor: '#10b981',
      data: []
    }
  ]
});
const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: { display: false },
    title: { display: true, text: 'Punteggi totali per compilatore' }
  },
  scales: {
    y: { beginAtZero: true }
  }
});

watch(groupByPerson, (val) => {
  if (val) updateChart();
});
watch(groupedByResult, () => {
  if (groupByPerson.value) updateChart();
});

function updateChart() {
  chartData.value.labels = groupedByResult.value.map((_, idx) => `Compilatore #${idx + 1}`);
  chartData.value.datasets[0].data = groupedByResult.value.map(compilazione => getPunteggioTotale(compilazione));
  showChart.value = true;
}

onMounted(() => {
  if (groupByPerson.value) updateChart();
});
</script>

<style>
/* Spinner animation (tailwindcss animate-spin già incluso, ma aggiungo fallback) */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>