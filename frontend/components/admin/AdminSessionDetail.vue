<script lang="ts" setup>
const props = defineProps({
  session: {
    type: Object,
    required: true,
  },
});
// @ts-ignore
const token = useCookie("token").value;
</script>
<template>
  <div class="w-1/5 flex flex-col items-center gap-2 py-10 px-2 border-l-1 border-gray-200">
    <div class="flex flex-col items-center gap-2">
      <span class="text-red-400" v-if="token == session.Token">Sei tu!</span>
      <span class="text-gray-400">{{ session.ID_Sessione }}</span>
      <Avatar shape="circle" size="xlarge" :alt="`${session.nome} ${session.cognome}`"
        :image="`https://ui-avatars.com/api/?name=${session.Nome}+${session.Cognome}&rounded=true&bold=true&background=random`"
        class="w-24 h-24 rounded-full" style="background-color: #f3f4f6" />
    </div>
    <div class="flex flex-col items-center">
      <span class="text-xl font-bold">{{ session.Nome }} {{ session.Cognome }}</span>
      <span class="text-gray-400 font-thin">@{{ session.Username }}</span>
    </div>
    <Divider />
    <div class="flex flex-col items-center">
      <span class="text-gray-600">Creata il</span>
      <span class="text-gray-400 font-thin">{{ session.DataInizio }}</span>
    </div>
    <div class="flex flex-col items-center">
      <Button v-if="session.Sospeso == 0 && token != session.Token" :disabled="false"
        @click="$emit('delete-session', session.Token)" icon="pi pi-trash" class="w-full mt-4" size="small"
        label="Sospendi" severity="warn" rounded />
      <Button v-if="session.Sospeso == 0 && token == session.Token" :disabled="true" icon="pi pi-trash"
        class="w-full mt-4 opacity-50 cursor-not-allowed" size="small" label="Sospendi" severity="warn" rounded
        v-tooltip.left="{
          value: 'Non puoi sospenderti da solo',
          pt: {
            arrow: {
              style: {
                borderBottomColor: '!bg-primary',
              },
            },
            text: '!bg-primary !text-primary-contrast',
          },
        }" />
    </div>
  </div>
</template>