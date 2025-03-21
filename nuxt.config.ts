// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from "@tailwindcss/vite";
import Lara from "@primeuix/themes/lara";
export default defineNuxtConfig({
  compatibilityDate: "2024-11-01",
  devtools: { enabled: true },
  modules: ["@nuxt/icon", "@vueuse/nuxt", "@primevue/nuxt-module", "@nuxt/image", "@nuxt/fonts"],
  css: ["~/assets/css/main.css"],
  routeRules: {
    "/pages/**": {
      cors: true,
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
