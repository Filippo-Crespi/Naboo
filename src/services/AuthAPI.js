import API from "./API";

export default {
  postLogin(data) {
    return API().post("/services/login.php", data);
  },
  postRegister(data) {
    return API().post("/services/register.php", data);
  },
  postUser(data) {
    return API().post(`/services/postUser.php`, data);
  },
};
