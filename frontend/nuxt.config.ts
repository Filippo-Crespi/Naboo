import lara from "@primeuix/themes/lara";
export default defineNuxtConfig({
  compatibilityDate: "2025-05-15",
  devtools: { enabled: true },
  modules: [
    "@nuxt/fonts",
    "@nuxt/icon",
    "@nuxt/image",
    "@nuxtjs/tailwindcss",
    "@vueuse/nuxt",
    "@primevue/nuxt-module",
  ],
  primevue: {
    options: {
      theme: { preset: lara },
    },
  },
});
