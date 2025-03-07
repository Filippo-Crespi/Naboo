// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: "2024-11-01",
  devtools: { enabled: true },
  modules: [
    "@nuxt/icon",
    "@nuxtjs/google-fonts",
    "@vueuse/nuxt",
    "@nuxt/ui",
    "@nuxtjs/tailwindcss",
  ],
  css: ["~/assets/css/main.css"],
  googleFonts: {
    families: {
      Rubik: true,
    },
  },
});
