<script setup></script>

<template>
  <div class="flex h-screen w-full">
    <div class="w-1/2 p-2 bg-blue-500">
      <div class="flex flex-col justify-center items-center h-full">
        <h1 class="text-4xl font-bold">Crea un modulo</h1>
        <p class="text-base">Registrazione necessaria</p>
        <router-link
          to="/register"
          class="mt-4 px-4 py-2 w-1/3 text-center bg-white rounded-2xl hover:bg-gray-200"
          >Registrati</router-link
        >
        <p class="mt-4">
          Gi&agrave; registrato?
          <router-link to="/login" class="text-white">Entra</router-link>
        </p>
      </div>
    </div>
    <div class="w-1/2 p-2 bg-violet-500">
      <div class="flex flex-col justify-center items-center h-full">
        <h1 class="text-4xl font-bold">Compila un modulo</h1>
        <input
          type="text"
          class="mt-4 px-4 py-2 w-1/3 text-center bg-white rounded-2xl"
          placeholder="Inserisci il codice"
        />
        <button
          class="mt-4 px-4 py-2 w-1/3 text-center bg-white rounded-2xl hover:bg-gray-200"
        >
          Entra
        </button>
      </div>
    </div>
  </div>
</template>
