import axios from 'axios';

const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API,
});

// Add interceptors for handling global errors
axiosInstance.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    return Promise.reject(error);
  },
);

export default axiosInstance;