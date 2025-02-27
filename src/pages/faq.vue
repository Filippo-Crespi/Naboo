<script setup>
import Accordion from "primevue/accordion";
import AccordionPanel from "primevue/accordionpanel";
import AccordionHeader from "primevue/accordionheader";
import AccordionContent from "primevue/accordioncontent";
import { faqs } from "../scripts/utility/faq.js";
</script>

<template>
  <div>
    <div class="pt-30 px-12 flex gap-8 flex-col">
      <div class="flex justify-center items-center gap-4">
        <i class="pi pi-question-circle !text-4xl"></i>
        <h1 class="text-center font-bold text-5xl">FAQ</h1>
      </div>
      <Accordion value="0">
        <AccordionPanel v-for="faq of faqs" :key="faq.id" :value="faq.id">
          <AccordionHeader>{{ faq.title }}</AccordionHeader>
          <AccordionContent
            ><p class="m-0">
              {{ faq.content }}
            </p>
          </AccordionContent>
        </AccordionPanel>
      </Accordion>
    </div>
  </div>
</template>
