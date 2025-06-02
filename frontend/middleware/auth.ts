export default defineNuxtRouteMiddleware(async (to) => {
  // const session_uuid = useCookie("session_uuid").value;
  // if (!session_uuid) {
  //   return navigateTo("/auth");
  // }
  // await useFetch(
  //   "http://localhost:8085/api/autenticazione/sessioni.php?session_uuid=" + session_uuid,
  //   {
  //     method: "GET",
  //     headers: {
  //       "Content-Type": "application/json",
  //     },
  //     onResponseError: (error) => {
  //       if (error.response.status === 401) {
  //         return navigateTo("/auth");
  //       }
  //     },
  //   }
  // );
  // navigateTo(to.fullPath, { replace: true });
  return true; // Allow navigation to proceed
});
