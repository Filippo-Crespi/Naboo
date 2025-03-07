<script lang="ts" setup>
import { Suspense } from "vue";
import { onMounted, ref } from "vue";
import FormAPI from "../../../services/FormAPI";
import { getToken } from "../../../scripts/helper";
import FormCardSkeleton from "../forms/formCardSkeleton.vue";
import FormCard from "../forms/formCard.vue";
const forms = ref();
try {
  // const res = await FormAPI.getUserForm(getToken());
  // forms.value = res.data.forms;
} catch (err) {
  console.error(err);
}
</script>

<template>
  <div>
    <Suspense>
      <template #default>
        <FormCard />
      </template>
      <template #fallback>
        <div class="w-full flex justify-around">
          <FormCardSkeleton />
          <FormCardSkeleton />
          <FormCardSkeleton />
        </div>
      </template>
    </Suspense>
  </div>
</template>

<style></style>
