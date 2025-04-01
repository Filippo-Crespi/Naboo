<script lang="ts" setup>
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

const sendMail = () => {
  window.open(`mailto:${props.user.mail}`, "_blank");
};
</script>
<template>
  <div class="w-1/5 flex flex-col items-center gap-4 py-10 px-2 border-l-1 border-gray-200">
    <div class="flex flex-col items-center gap-2">
      <span class="text-gray-400">{{ user.ID_Utente }}</span>
      <Avatar
        shape="circle"
        size="xlarge"
        :alt="`${user.nome} ${user.cognome}`"
        :image="`https://ui-avatars.com/api/?name=${user.nome}+${user.cognome}&rounded=true&bold=true&background=random`"
        class="w-24 h-24 rounded-full"
        style="background-color: #f3f4f6" />
    </div>
    <div class="flex flex-col items-center">
      <span class="text-xl font-bold">{{ user.nome }} {{ user.cognome }}</span>
      <span class="text-gray-400 font-thin">@{{ user.username }}</span>
    </div>
    <div class="flex flex-col items-center">
      <span class="font-medium">Data registrazione</span>
      <span class="text-gray-400 font-thin">{{ user.dataReg }}</span>
    </div>
    <div class="flex flex-col items-center gap-2">
      <ButtonGroup>
        <Button
          @click="sendMail"
          icon="pi pi-envelope"
          label="Scrivi"
          class="w-full"
          size="small"
          severity="primary"
          rounded />
        <Button
          @click="$emit('deleteUser', user.id)"
          icon="pi pi-trash"
          class="w-full"
          size="small"
          label="Elimina"
          severity="danger"
          rounded />
      </ButtonGroup>
    </div>
    <Divider>Moduli creati</Divider>
    <ScrollPanel>
      <!-- Moduli creati -->
    </ScrollPanel>
  </div>
</template>

<style></style>
