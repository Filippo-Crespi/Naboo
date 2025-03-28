<script lang="ts" setup>
import type { Response, User } from "~/types";

const router = useRouter();
definePageMeta({
  layout: "no-header",
});
const isDrawerVisible = ref(false);

onBeforeMount(() => {
  const isLogged = useCookie("token").value ?? false;
  // contollo validita token

  if (!isLogged) {
    router.push("/auth/login");
    return;
  }
});

const token = useCookie("token").value;
const user = useCookie("user") as Ref<User>;

try {
  const { data } = await useFetch(
    "https://andrellaveloise.it/users?token=" + token + "&stringa=nome+cognome",
    {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    }
  );
  const userData = data.value as Response;
  user.value = userData.data[0];
} catch (err) {
  console.log(err);
}
</script>
<template>
  <AccountDrawer v-model:visible="isDrawerVisible" />
  <div>
    <DashboardToolbar @open-drawer="isDrawerVisible = true" />
    <FormContainer class="!p-8" />
  </div>
</template>
