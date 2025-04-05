<script lang="ts" setup></script>

<template>
  <div class="min-h-[100dvh] w-screen">
    <div class="h-15 w-full py-8 flex justify-between items-center px-16">
      <div class="flex gap-2 items-center">
        <img src="/imgs/logo.png" class="h-8" alt="" />
        <span class="text-3xl font-extrabold">NABOO</span>
      </div>
      <nav class="flex gap-2">
        <Button
          variant="link"
          as="a"
          href="#comefunziona"
          icon="pi-question-circle"
          label="Come funziona" />
        <Button
          variant="link"
          as="a"
          href="#funzionalita"
          icon="pi-cog"
          label="Funzionalit&agrave;" />
      </nav>
      <Button asChild v-slot="slotProps" rounded raised>
        <RouterLink to="/home" v-bind="slotProps">Entra</RouterLink>
      </Button>
    </div>
    <div class="flex mt-16 pt-24 items-start justify-start px-16 h-[calc(100dvh-7.75rem)]">
      <div class="flex flex-col gap-6 items-start">
        <Button
          label="Facile da usare"
          icon="pi pi-sparkles"
          rounded
          raised
          outlined
          severity="secondary" />
        <div class="flex flex-col gap-12">
          <div class="flex flex-col gap-2">
            <span class="text-7xl font-bold"
              >Crea moduli <br />
              in pochissimi minuti</span
            >
            <span class="text-lg opacity-50"
              >Naboo ti aiuta a creare moduli online in modo semplice e veloce</span
            >
          </div>
          <div>
            <Button asChild v-slot="slotProps" rounded raised>
              <RouterLink to="/home" v-bind="slotProps">Inizia subito</RouterLink>
            </Button>
          </div>
        </div>
      </div>
    </div>
    <div id="funzionalita" class="flex flex-col px-16 gap-8 bg-[#eafaf6] py-16">
      <div class="flex flex-col gap-2 items-center">
        <span class="text-center font-bold text-4xl">Funzionalit&agrave;</span>
        <span class="text-lg opacity-50"
          >Tutto ci&ograve; di cui hai bisogno per creare moduli professionali</span
        >
      </div>
      <div class="flex items-center justify-center gap-12 flex-wrap">
        <GenericCard
          title="Design personalizzabile"
          emoji="🎨"
          description="Personalizza colori e font per rispecchiare al meglio il tuo stile" />
        <GenericCard
          title="Responsive su mobile"
          emoji="📱"
          description="I tuoi moduli si adattano perfettamente ad ogni dispositivo" />
        <GenericCard
          title="Analisi in tempo reale"
          emoji="📊"
          description="Visualizza subito le risposte e le statistiche dei moduli che hai creato" />
        <GenericCard
          title="Design personalizzabile"
          emoji="🎨"
          description="Personalizza colori e font per rispecchiare al meglio il tuo stile" />
        <GenericCard
          title="Responsive su mobile"
          emoji="📱"
          description="I tuoi moduli si adattano perfettamente ad ogni dispositivo" />
        <GenericCard
          title="Analisi in tempo reale"
          emoji="📊"
          description="Visualizza subito le risposte e le statistiche dei moduli che hai creato" />
      </div>
    </div>
    <div id="comefunziona" class="flex flex-col px-16 gap-8 py-16">
      <div class="flex flex-col gap-2 items-center">
        <span class="text-center font-bold text-4xl">Come funziona?</span>
        <span class="text-lg opacity-50"
          >Creare un modulo &egrave; facile come qualcosa che devo inventarmi</span
        >
      </div>
    </div>
  </div>
</template>
