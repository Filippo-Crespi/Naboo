import axios from "axios";

export default (url = "http://localhost:4000/") => {
  return axios.create({
    baseURL: url,
  });
};
