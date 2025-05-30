<script lang="ts" setup>
import type { Response } from '~/types';
import { ref, onMounted } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

const toast = useToast();
const createdModules = ref<any[]>([]);

onMounted(async () => {
  try {
    const res: Response = await $fetch("https://andrellaveloise.it/forms/users?ID_Utente=" + props.user.ID_Utente, {
      method: "GET",
      onResponseError({ response }) {
        if (response.status === 404) {
          throw new Error(response._data.message);
        } else if (response.status === 500) {
          throw new Error("Internal Server Error");
        }
      },
    });
    if (res.success && Array.isArray(res.data)) {
      createdModules.value = res.data;
    }
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 2500,
    });
  }
});

// @ts-ignore
const loggedUser = useCookie("user").value as User;

const sendMail = () => {
  window.open(`mailto:${props.user.Mail}`, "_blank");
};
</script>
<template>
  <Toast />
  <div class="w-1/5 flex flex-col items-center gap-4 py-10 px-2 border-l-1 border-gray-200">
    <div class="flex flex-col items-center gap-2">
      <span class="text-red-400" v-if="loggedUser.Email == user.Email">Sei tu!</span>
      <span class="text-gray-400">{{ user.ID_Utente }}</span>
      <Avatar shape="circle" size="xlarge" :alt="`${user.Nome || ''} ${user.Cognome || ''}`" :image="`https://ui-avatars.com/api/?name=${user.Nome || ''}+${user.Cognome || ''
        }&rounded=true&bold=true&background=random`" class="w-24 h-24 rounded-full"
        style="background-color: #f3f4f6" />
    </div>
    <div class="flex flex-col items-center">
      <span class="text-xl font-bold">{{ user.Nome || "" }} {{ user.Cognome || "" }}</span>
      <span class="text-gray-400 font-thin">@{{ user.Username }}</span>
    </div>
    <div class="flex flex-col items-center">
      <span class="font-medium">Data registrazione</span>
      <span class="text-gray-400 font-thin">{{ user.DataReg }}</span>
    </div>
    <div class="flex flex-col items-center gap-2">
      <ButtonGroup>
        <Button @click="sendMail" icon="pi pi-envelope" label="Scrivi" class="w-full" size="small" severity="primary"
          rounded :disabled="loggedUser?.Email == user.Email" v-if="loggedUser?.Email != user.Email" />
        <Button @click="sendMail" icon="pi pi-envelope" label="Scrivi" class="w-full" size="small" severity="primary"
          rounded :disabled="true" v-else v-tooltip.left="{
            value: 'Ehi, vuoi mandarti una mail da solo?',
            pt: {
              arrow: { style: { borderBottomColor: '!bg-primary' } },
              text: '!bg-primary !text-primary-contrast',
            },
          }" />
        <Button @click="$emit('delete-user', user.ID_Utente)" icon="pi pi-trash" class="w-full" size="small"
          label="Elimina" severity="danger" rounded :disabled="loggedUser?.Email == user.Email"
          v-if="loggedUser?.Email != user.Email" />
        <Button @click="$emit('delete-user', user.ID_Utente)" icon="pi pi-trash" class="w-full" size="small"
          label="Elimina" severity="danger" rounded :disabled="true" v-else v-tooltip.left="{
            value: 'Non puoi eliminarti da solo',
            pt: {
              arrow: { style: { borderBottomColor: '!bg-primary' } },
              text: '!bg-primary !text-primary-contrast',
            },
          }" />
      </ButtonGroup>
    </div>
    <Divider>Moduli creati</Divider>
    <ScrollPanel style="max-height: 250px; width: 100%;">
      <div v-if="createdModules.length === 0" class="text-gray-400 text-center py-2">Nessun modulo creato</div>
      <div v-else class="flex flex-col gap-2">
        <div v-for="modulo in createdModules" :key="modulo.ID_Modulo"
          class="border border-gray-200 rounded p-2 bg-gray-50">
          <div class="font-semibold text-[#10b981]">{{ modulo.Titolo }}</div>
          <div class="text-xs text-gray-500 truncate">{{ modulo.Descrizione }}</div>
          <div class="text-xs text-gray-400 mt-1">Codice: <span class="font-mono">{{ modulo.Codice }}</span></div>
        </div>
      </div>
    </ScrollPanel>
  </div>
</template>

<style></style>