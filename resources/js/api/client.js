import axios from 'axios';

/**
 * Cliente HTTP preconfigurado para comunicarse con la API REST del backend.
 * Utiliza axios con base URL `/api/v1` y cabeceras JSON estándar.
 *
 * @type {import('axios').AxiosInstance}
 */
const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

export default api;
