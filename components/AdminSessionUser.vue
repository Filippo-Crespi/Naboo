<script lang="ts" setup>
const props = defineProps({
  user: {
    type: Object,
    // required: true,
  },
});
</script>
<template>
  <div>
    <div class="flex items-center gap-2 w-full justify-between">
      <div class="flex items-center gap-2">
        <Badge value="" :severity="user?.sospeso ? 'danger' : 'success'" />
        <span class="text-2xl">Nome Cognome</span>
      </div>
      <div>
        <ButtonGroup>
          <Button rounded><Icon name="bx:show-alt" /></Button>
          <Button severity="danger" rounded>
            <Icon name="material-symbols:delete-forever-outline-rounded" /></Button
        ></ButtonGroup>
      </div>
    </div>
    <Divider />
  </div>
</template>

<style></style>
