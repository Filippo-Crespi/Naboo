<script lang="ts" setup>
onMounted(() => {
  useCookie("user").value = null;
  useCookie("token").value = null;
});

const op = ref();

const toggle = (event: any) => {
  op.value.toggle(event);
};
const functionalities = [
  {
    title: "Design personalizzabile",
    description: "Personalizza colori e font per rispecchiare al meglio il tuo stile",
    emoji: "🎨",
  },
  {
    title: "Responsive su mobile",
    description: "I tuoi moduli si adattano perfettamente ad ogni dispositivo",
    emoji: "📱",
  },
  {
    title: "Analisi in tempo reale",
    description: "Visualizza subito le risposte e le statistiche dei moduli che hai creato",
    emoji: "📊",
  },
  {
    title: "Notifiche immediate",
    description: "Ricevi un avviso quando qualcuno compila i tuoi moduli",
    emoji: "🔔",
  },
  // 2 placeholder
  {
    title: "Design personalizzabile",
    description: "Personalizza colori e font per rispecchiare al meglio il tuo stile",
    emoji: "🎨",
  },
  {
    title: "Responsive su mobile",
    description: "I tuoi moduli si adattano perfettamente ad ogni dispositivo",
    emoji: "📱",
  },
];
const howItWorks = [
  {
    title: "Crea un account",
    description: "Registrati e accedi al tuo account",
    step: "1",
  },
  {
    title: "Crea un modulo",
    description: "Crea un modulo e aggiungi le domande che vuoi",
    step: "2",
  },
  {
    title: "Condividi il link",
    description: "Condividi il link del modulo con chi vuoi",
    step: "3",
  },
];
</script>

<template>
  <Button class="!fixed bottom-5 right-5 z-50" icon="pi pi-arrow-up" as="a" href="#" rounded raised />
  <Button class="!fixed bottom-5 left-5 z-50" icon="pi pi-question-circle" severity="help" rounded raised
    @click="toggle" />

  <Popover ref="op" target="toggle" position="bottom" class="w-100">
    <form class="p-4 flex flex-col gap-4">
      <span>
        <h1 class="text-2xl font-bold mb-2">Contattaci</h1>
        <p class="text-sm">
          Hai domande o hai bisogno di assistenza? <br />
          Non esitare a contattarci!
        </p>
      </span>
      <div class="flex flex-col gap-2">
        <label for="name" class="font-medium">Name</label>
        <InputText type="text" id="name" class="border rounded p-2" placeholder="Il tuo nome" />
      </div>

      <div class="flex flex-col gap-2">
        <label for="email" class="font-medium">Email</label>
        <InputText type="email" id="email" placeholder="tua@email.com" />
      </div>

      <div class="flex flex-col gap-2">
        <label for="message" class="font-medium">Messaggio</label>
        <Textarea id="message" rows="4" class="border rounded p-2" placeholder="Your message..."></Textarea>
      </div>

      <Button label="Invia" disabled rounded raised class="w-full" v-tooltip.right="{
        value: 'Non ancora disponibile',
        pt: {
          arrow: {
            style: {
              borderBottomColor: '!bg-primary',
            },
          },
          text: '!bg-primary !text-primary-contrast',
        },
      }" />
    </form>
  </Popover>
  <div class="min-h-[100vh] w-screen">
    <div class="h-30 w-full py-8 flex justify-between items-center md:px-16 px-8">
      <div class="flex gap-2 items-center flex-1">
        <img src="/imgs/logo.png" class="h-8" alt="" />
        <span class="text-3xl font-extrabold">NABOO</span>
      </div>
      <nav class=" gap-2 my-0 mx-auto hidden md:flex">
        <Button variant="link" as="a" href="#funzionalita" icon="pi pi-cog" label="Funzionalit&agrave;" />
        <Button variant="link" as="a" href="#comefunziona" icon="pi pi-question-circle" label="Come funziona" />
      </nav>
      <div class="flex-1 flex justify-end items-center gap-4">
        <span class="text-gray-400 hidden md:block">Hai gi&agrave; un account?</span>
        <Button asChild v-slot="slotProps" rounded raised>
          <RouterLink to="/auth/login" v-bind="slotProps">Accedi</RouterLink>
        </Button>
      </div>
    </div>
    <div class="flex md:mt-16 md:pt-24 pt-0 items-start justify-start px-16 h-[calc(100vh-11.5rem)]">
      <div class="flex flex-col gap-6 items-start w-2/3">
        <Button label="User Friendly" icon="pi pi-sparkles" rounded raised outlined severity="secondary" disabled />
        <div class="flex flex-col gap-12">
          <div class="flex flex-col gap-2">
            <span class="text-5xl md:text-7xl font-bold">Crea moduli in
              <span
                class="bg-clip-text text-transparent bg-gradient-to-r from-[#30e2eb] to-[#80c1b3] animate-text-shine">pochissimi</span>
              minuti</span>
            <span class="text-lg opacity-50">Naboo ti aiuta a creare moduli online in modo semplice e veloce</span>
          </div>
          <div>
            <Button asChild v-slot="slotProps" rounded raised size="large">
              <RouterLink to="/home" v-bind="slotProps">Inizia subito</RouterLink>
            </Button>
          </div>
        </div>
      </div>
      <div class="w-1/3 relative">
        <img
          class="hidden md:block circle-1 absolute left-[calc(50%-6rem)] top-1/2 -translate-x-1/2 -translate-y-15 w-3/4"
          src="/imgs/landing/circle1.png" />
        <img
          class="hidden md:block circle-2 absolute left-[calc(50%+4rem)] top-1/2 -translate-x-1/2 translate-y-15 w-3/4"
          src="/imgs/landing/circle2.png" />
        <img class="hidden md:block form absolute shadow-xl rounded-xl left-1/2 top-1/2 -translate-x-1/2"
          src="/imgs/landing/form.png" />
      </div>
    </div>
    <div id="funzionalita" class="flex flex-col px-16 gap-8 bg-[#eafaf6] py-16">
      <div class="flex flex-col gap-2 items-center">
        <span class="text-center font-bold text-4xl">Funzionalit&agrave;</span>
        <span class="text-lg text-center opacity-50">Tutto ci&ograve; di cui hai bisogno per creare moduli
          professionali</span>
      </div>
      <div class="flex items-center justify-center gap-12 flex-wrap">
        <GenericCard v-for="(card, index) in functionalities" :key="index" :description="card.description"
          :emoji="card.emoji" :title="card.title" />
      </div>
    </div>
    <div id="comefunziona" class="flex flex-col px-16 gap-8 py-16">
      <div class="flex flex-col gap-2 items-center">
        <span class="text-center font-bold text-4xl">Come funziona?</span>
        <span class="text-lg text-center opacity-50">Creare un modulo &egrave; facile come qualcosa che devo
          inventarmi</span>
      </div>
      <div class="flex flex-col gap-8 md:gap-0 md:flex-row  items-center">
        <GenericStep v-for="(card, index) in howItWorks" :key="index" :title="card.title"
          :description="card.description" :step="card.step" :class="{
            'flex-1': index !== 1, // primo e terzo
            'mx-auto': index === 1, // elemento centrale
          }" />
      </div>
    </div>
    <footer class="bg-[#eafaf6] py-8">
      <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-6">
        <!-- Logo / Titolo -->
        <div class="text-lg font-semibold flex-1">
          <!-- Da sostituire con il tuo logo o nome del sito -->
          <a href="https://cobianchi.it" target="_blank">
            <img src="/imgs/logo.png" class="h-12" alt="" />
          </a>
        </div>

        <!-- Link di navigazione secondaria -->
        <nav class="flex gap-4 text-sm my-0 mx-auto">
          <a href="#" class="hover:underline">Privacy</a>
          <a href="#" class="hover:underline">Contatti</a>
          <a href="#" class="hover:underline">Termini</a>
        </nav>

        <!-- Social (placeholder) -->
        <div class="flex justify-end gap-4 flex-1">
          <a href="#" class="hover:text-gray-400"><i class="pi pi-facebook"></i></a>
          <a href="#" class="hover:text-gray-400"><i class="pi pi-instagram"></i></a>
          <a href="#" class="hover:text-gray-400"><i class="pi pi-twitter"></i></a>
        </div>
      </div>

      <!-- Riga finale -->
      <div class="text-center text-xs text-gray-250 mt-6">
        &copy; {{ new Date().getFullYear() }} Naboo. Tutti i diritti riservati.
      </div>
    </footer>
  </div>
</template>

<style scoped>
.circle-1 {
  animation: pulse 4s ease-in-out infinite;
}

.circle-2 {
  animation: pulse 4s ease-in-out infinite;
  animation-delay: 2s;
}

.form {
  animation: rotate 4s ease-in-out infinite;
  transform: rotate(-5deg);
}

@keyframes rotate {
  0% {
    transform: rotate(-5deg);
  }

  50% {
    transform: rotate(5deg);
  }

  100% {
    transform: rotate(-5deg);
  }
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.05);
  }

  100% {
    transform: scale(1);
  }
}

@keyframes textShine {
  0% {
    background-position: 0% 50%;
  }

  100% {
    background-position: 100% 50%;
  }
}

.animate-text-shine {
  background-size: 500% auto;
  animation: textShine 2s infinite alternate;
}
</style>