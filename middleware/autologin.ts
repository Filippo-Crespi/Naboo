import type { Response } from "~/types";

export default defineNuxtRouteMiddleware(async (to, from) => {
  const token = useCookie("token").value;
  if (!token) {
    return;
  }
  const res: Response = await $fetch("https://andrellaveloise.it/sessions", {
    method: "POST",
    body: {
      Token: token,
    },
  });
  if (res.success) {
    useRouter().push("/dashboard");
  }
});
