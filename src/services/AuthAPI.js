import API from "./API";

export default {
  postLogin(data) {
    return API().post("/services/login.php", data);
  },
  postRegister(data) {
    return API().post("/services/register.php", data);
  },
  getUser(token) {
    return API().get(`/services/getUser.php?token=${token}`);
  },
};
