import router from "@/router";

export function goTo(path) {
  router.push(path);
}

export function getToken() {
  return document.cookie.split("=")[1];
}

export async function compileForm() {
  return;
}

export function codeRegex(code) {
  return /^[a-zA-Z0-9]{6}$/.test(code);
}
