<script lang="ts" setup>

// Modulo iniziale vuoto
const modulo = ref({

  "titolo": "Modulo di Valutazione - Informatica Generale",
  "descrizione": "Questo modulo copre concetti fondamentali di informatica, algoritmi, strutture dati, basi di programmazione e logica.",
  "sezioni": [
    {
      "nome": "Logica e Algoritmi",
      "domande": [
        {
          "testo": "Un algoritmo è una sequenza finita di istruzioni non ambigue.",
          "descrizione": "Valuta se la definizione di algoritmo è corretta.",
          "tipologia": 1,
          "risposte": [
            {
              "testo": "Vero",
              "punteggio": 1
            },
            {
              "testo": "Falso",
              "punteggio": 0
            }
          ]
        },
        {
          "testo": "Spiega cos’è un diagramma di flusso e a cosa serve.",
          "descrizione": "Risposta aperta",
          "tipologia": 3
        },
        {
          "testo": "Quali di questi sono strutture di controllo?",
          "descrizione": "Seleziona tutte le risposte corrette.",
          "tipologia": 4,
          "risposte": [
            {
              "testo": "if",
              "punteggio": 1
            },
            {
              "testo": "while",
              "punteggio": 1
            },
            {
              "testo": "echo",
              "punteggio": 0
            },
            {
              "testo": "for",
              "punteggio": 1
            }
          ]
        }
      ]
    },
    {
      "nome": "Programmazione in C++",
      "domande": [
        {
          "testo": "Cosa rappresenta il simbolo `::` in C++?",
          "descrizione": "Risposta aperta, spiegare brevemente l’operatore di risoluzione di ambito.",
          "tipologia": 3
        },
        {
          "testo": "Quale dei seguenti è un ciclo precondizionale?",
          "descrizione": "Domanda a scelta singola.",
          "tipologia": 4,
          "risposte": [
            {
              "testo": "do...while",
              "punteggio": 0
            },
            {
              "testo": "for",
              "punteggio": 1
            },
            {
              "testo": "switch",
              "punteggio": 0
            }
          ]
        },
        {
          "testo": "Il C++ supporta la programmazione orientata agli oggetti.",
          "descrizione": "Domanda vero/falso.",
          "tipologia": 1,
          "risposte": [
            {
              "testo": "Vero",
              "punteggio": 1
            },
            {
              "testo": "Falso",
              "punteggio": 0
            }
          ]
        }
      ]
    },
    {
      "nome": "HTML e CSS",
      "domande": [
        {
          "testo": "Quali di questi sono elementi semantici in HTML5?",
          "descrizione": "Seleziona tutte le risposte corrette.",
          "tipologia": 4,
          "risposte": [
            {
              "testo": "<header>",
              "punteggio": 1
            },
            {
              "testo": "<div>",
              "punteggio": 0
            },
            {
              "testo": "<section>",
              "punteggio": 1
            },
            {
              "testo": "<footer>",
              "punteggio": 1
            }
          ]
        },
        {
          "testo": "Spiega a cosa serve il selettore `nth-child()` in CSS.",
          "descrizione": "Risposta aperta",
          "tipologia": 3
        },
        {
          "testo": "Un ID può essere utilizzato più volte nella stessa pagina HTML.",
          "descrizione": "Domanda vero/falso",
          "tipologia": 1,
          "risposte": [
            {
              "testo": "Vero",
              "punteggio": 0
            },
            {
              "testo": "Falso",
              "punteggio": 1
            }
          ]
        }
      ]
    },
    {
      "nome": "JavaScript e TypeScript",
      "domande": [
        {
          "testo": "Cos'è una Promise in JavaScript?",
          "descrizione": "Spiega brevemente il concetto di Promise e un caso d'uso.",
          "tipologia": 3
        },
        {
          "testo": "Quale parola chiave permette di dichiarare una variabile che non può essere riassegnata?",
          "descrizione": "Domanda a scelta singola.",
          "tipologia": 4,
          "risposte": [
            {
              "testo": "let",
              "punteggio": 0
            },
            {
              "testo": "const",
              "punteggio": 1
            },
            {
              "testo": "var",
              "punteggio": 0
            }
          ]
        },
        {
          "testo": "`TypeScript` è un superset tipizzato di `JavaScript`.",
          "descrizione": "Domanda vero/falso.",
          "tipologia": 1,
          "risposte": [
            {
              "testo": "Vero",
              "punteggio": 1
            },
            {
              "testo": "Falso",
              "punteggio": 0
            }
          ]
        }
      ]
    }
  ]
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
    <!-- Titolo e descrizione del modulo -->
    <div class="mb-4">
      <h1 class="text-2xl font-bold">{{ modulo.titolo }}</h1>
      <h2 class="text-xl">{{ modulo.descrizione }}</h2>
    </div>

    <!-- Sezioni -->
    <div v-for="(sezione, sezioneIndex) in modulo.sezioni" :key="sezioneIndex" class="mb-6 border p-4 rounded">
      <h3 class="text-lg font-semibold">{{ sezione.nome }}</h3>

      <!-- Domande -->
      <div v-for="(domanda, domandaIndex) in sezione.domande" :key="domandaIndex" class="mt-4 border p-4 rounded">
        <h4 class="text-md font-medium">Domanda: {{ domanda.testo }}</h4>
        <p class="text-gray-600">{{ domanda.descrizione }}</p>

        <!-- Risposte -->
        <div v-if="domanda.risposte && domanda.tipologia !== 3" class="mt-2 flex flex-col gap-2">
          <div v-for="(risposta, rispostaIndex) in domanda.risposte" :key="rispostaIndex"
            class="flex items-center gap-2">
            <RadioButton :inputId="`domanda-${domandaIndex}-risposta-${rispostaIndex}`" :value="risposta.punteggio"
              :name="`domanda-${domandaIndex}`" disabled />
            <label :for="`domanda-${domandaIndex}-risposta-${rispostaIndex}`">{{ risposta.testo }}</label>
          </div>
        </div>
        <Textarea v-else-if="domanda.tipologia === 3" class="w-full mt-2" :auto-resize="true"
          disabled>Scrivi la tua risposta</Textarea>
        <input type="text" v-else-if="domanda.tipologia === 2" placeholder="Scrivi la tua risposta" />
      </div>
    </div>
  </div>
</template>

<style></style>