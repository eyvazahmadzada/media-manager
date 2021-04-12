import axios from "axios";

// My apache server directory
let baseURL = "http://localhost/projects/media-manager/public/server/api/";

if (process.env.NODE_ENV === "production") {
  baseURL = "server/api/";
}

// Creating axios instance with baseURL
const instance = axios.create({ baseURL });

export default instance;
