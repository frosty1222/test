// src/axios.js
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useMessage } from 'naive-ui';

const instance = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  timeout: 1000,
  headers: { 'Content-Type': 'application/json' }
});

instance.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

instance.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    const router = useRouter();
    const message = useMessage();

    if (error.response && error.response.status === 401) {
      localStorage.removeItem('auth_token');
      delete axios.defaults.headers.common['Authorization'];
      message.error('Session expired. Please log in again.');
      router.push('/');
    } else {
      message.error(error.response.data.message || 'An error occurred. Please try again.');
    }
    return Promise.reject(error);
  }
);

export default instance;
