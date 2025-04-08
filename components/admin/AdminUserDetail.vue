<script lang="ts" setup>
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

// @ts-ignore
const loggedUser = useCookie("user").value as User;

const sendMail = () => {
  window.open(`mailto:${props.user.Mail}`, "_blank");
};
</script>
<template>
  <div class="w-1/5 flex flex-col items-center gap-4 py-10 px-2 border-l-1 border-gray-200">
    <div class="flex flex-col items-center gap-2">
      <span class="text-red-400" v-if="loggedUser.Email == user.Email">Sei tu!</span>
      <span class="text-gray-400">{{ user.ID_Utente }}</span>
      <Avatar
        shape="circle"
        size="xlarge"
        :alt="`${user.Nome || ''} ${user.Cognome || ''}`"
        :image="`https://ui-avatars.com/api/?name=${user.Nome || ''}+${
          user.Cognome || ''
        }&rounded=true&bold=true&background=random`"
        class="w-24 h-24 rounded-full"
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
        <Button
          @click="sendMail"
          icon="pi pi-envelope"
          label="Scrivi"
          class="w-full"
          size="small"
          severity="primary"
          rounded
          :disabled="loggedUser?.Email == user.Email"
          v-tooltip.left="{
            value: 'ew vuoi mandarti una mail da solo?',
            pt: {
              arrow: {
                style: {
                  borderBottomColor: '!bg-primary',
                },
              },
              text: '!bg-primary !text-primary-contrast',
            },
          }" />
        <Button
          @click="$emit('delete-user', user.ID_Utente)"
          icon="pi pi-trash"
          class="w-full"
          size="small"
          label="Elimina"
          severity="danger"
          rounded
          :disabled="loggedUser?.Email == user.Email"
          v-tooltip.left="{
            value: 'Non puoi eliminarti da solo',
            pt: {
              arrow: {
                style: {
                  borderBottomColor: '!bg-primary',
                },
              },
              text: '!bg-primary !text-primary-contrast',
            },
          }" />
      </ButtonGroup>
    </div>
    <Divider>Moduli creati</Divider>
    <ScrollPanel>
      <!-- Moduli creati -->
    </ScrollPanel>
  </div>
</template>

<style></style>
