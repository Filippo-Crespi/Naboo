<script lang="ts" setup>
import { onMounted, ref } from "vue";
import FormAPI from "../../services/FormAPI";
import { getToken } from "../../scripts/helper";
const forms = ref();
try {
  const res = await FormAPI.getUserForm(getToken());
  forms.value = res.data.forms;
} catch (err) {
  console.error(err);
}
</script>
<template>
  <div>{{ forms }}</div>
</template>

<style></style>
