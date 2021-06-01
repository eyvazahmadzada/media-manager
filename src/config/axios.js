import axios from "axios";

// Development directory
let baseURL = "http://localhost/media-manager/public/server/api/";

if (process.env.NODE_ENV === "production") {
  baseURL = "server/api/";
}

// Creating axios instance with baseURL
const instance = axios.create({ baseURL });

export default instance;
