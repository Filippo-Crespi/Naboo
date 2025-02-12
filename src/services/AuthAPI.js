import API from "./API";

export default {
  postLogin(data) {
    return API().post("/auth/login.php", data);
  },
  postRegister(data) {
    return API().post("/auth/register.php", data);
  },
  getUser(token) {
    return API().get(`/auth/getUser.php?token=${token}`);
  },
};
