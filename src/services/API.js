import axios from "axios";

export default (url = "http://localhost/Forms/src/api/") => {
  return axios.create({
    baseURL: url,
  });
};
