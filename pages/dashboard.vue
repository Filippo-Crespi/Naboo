<script lang="ts" setup>
import { type Form, type Response, type User } from "~/types";

const router = useRouter();
const toast = useToast();
const isDrawerVisible = ref(false);
const isDialogVisible = ref(false);
const modulo = ref<Form>({
  Titolo: "",
  Descrizione: "",
  Token: "",
});

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

const creaModulo = async () => {
  const token = useCookie("token").value;
  try {
    const res: Response = await $fetch("https://andrellaveloise.it/forms/users", {
      method: "POST",
      body: {
        Token: token,
        Titolo: modulo.value.Titolo,
        Descrizione: modulo.value.Descrizione,
      },
      onResponseError({ response }) {
        if (response.status === 404) {
          throw new Error(response._data.message);
        }
      },
    });
    toast.add({
      severity: "success",
      summary: "Modulo creato",
      detail: "Il modulo è stato creato con successo",
      life: 2500,
    });
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Errore",
      detail: (error as Error).message,
      life: 2500,
    });
    await new Promise((resolve) => setTimeout(resolve, 1000));
    router.push("/auth/login");
    isDialogVisible.value = false;
  } finally {
    isDialogVisible.value = false;
  }
};
</script>
<template>
  <Dialog class="m-w-50" modal v-model:visible="isDialogVisible" pt:mask:class="backdrop-blur-sm">
    <div class="px-4 py-8 flex flex-col gap-6">
      <span class="text-3xl font-medium">Crea un nuovo modulo</span>
      <FloatLabel variant="on"
        ><InputText type="text" fluid v-model="modulo.Titolo" />
        <label>Titolo modulo</label></FloatLabel
      >
      <FloatLabel variant="on"
        ><Textarea v-model="modulo.Descrizione" fluid />
        <label>Descrizione modulo</label></FloatLabel
      >
      <Button
        class="w-full"
        label="Crea"
        icon="pi pi-plus"
        severity="primary"
        raised
        @click="creaModulo" />
    </div>
  </Dialog>
  <AccountDrawer v-model:visible="isDrawerVisible" />
  <div>
    <DashboardToolbar @open-drawer="isDrawerVisible = true" @create-form="isDialogVisible = true" />
    <FormContainer />
  </div>
</template>
