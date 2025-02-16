import router from "@/router";

export function goTo(path) {
  router.push(path);
}

export function getToken() {
  return document.cookie.split("=")[1];
}
