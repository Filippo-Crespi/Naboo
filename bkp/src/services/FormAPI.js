import API from "./API";

export default {
  getForm(data) {
    return API().post("/form/getForm.php", data);
  },
  getUserForm(token) {
    return API().post("/form/getUserForm.php", token);
  },
};
