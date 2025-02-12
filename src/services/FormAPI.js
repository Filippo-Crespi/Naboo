import API from "./API";

export default {
  getForm(data) {
    return API().post("/form/getForm.php", data);
  },
};
