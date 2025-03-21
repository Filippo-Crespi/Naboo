<script lang="ts" setup>
const router = useRouter();
definePageMeta({
  layout: "no-header",
});

onBeforeMount(() => {
  const isLogged = useCookie("token").value ?? false;
  if (!isLogged) {
    router.push("/auth/login");
    return;
  }
  // const { data } = useFetch("https://andrellaveloise.it/", {
  //   method: "GET",
  //   headers: {
  //     "Content-Type": "application/json",
  //   },
  //   body: { isLogged },
  // });
  // if (!data.value) {
  //   router.push("/auth/login");
  //   return;
  // }
});
</script>
<template>
  <div>
    <DashboardToolbar />
    <FormContainer class="!p-8" />
  </div>
</template>

<style></style>
