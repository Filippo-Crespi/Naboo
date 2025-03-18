// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from "@tailwindcss/vite";
import Lara from "@primeuix/themes/lara";
export default defineNuxtConfig({
  compatibilityDate: "2024-11-01",
  devtools: { enabled: true },
  modules: ["@nuxt/icon", "@nuxtjs/google-fonts", "@vueuse/nuxt", "@primevue/nuxt-module", "@nuxt/image"],
  css: ["~/assets/css/main.css"],
  routeRules: {
    "/pages/**": {
      cors: true,
    },
  },
  googleFonts: {
    families: {
      Rubik: true,
    },
  },
  vite: {
    plugins: [tailwindcss()],
  },
  primevue: {
    options: {
      theme: {
        preset: Lara,
      },
    },
  },
});