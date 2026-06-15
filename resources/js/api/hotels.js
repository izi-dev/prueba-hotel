import api from './client';

/**
 * @typedef {Object} City
 * @property {number} id - Identificador único de la ciudad.
 * @property {string} name - Nombre de la ciudad.
 */

/**
 * @typedef {Object} RoomType
 * @property {number} id - Identificador único del tipo de habitación.
 * @property {string} name - Nombre del tipo de habitación.
 */

/**
 * @typedef {Object} Accommodation
 * @property {number} id - Identificador único de la acomodación.
 * @property {string} name - Nombre de la acomodación.
 */

/**
 * @typedef {Object} Hotel
 * @property {number} id - Identificador único del hotel.
 * @property {string} name - Nombre comercial del hotel.
 * @property {string} address - Dirección física del hotel.
 * @property {number} city_id - Identificador de la ciudad asociada.
 * @property {string} city_name - Nombre de la ciudad asociada.
 * @property {string} nit - Número de identificación tributaria.
 * @property {number} max_rooms - Capacidad máxima de habitaciones del hotel.
 * @property {number} configured_rooms - Total de habitaciones ya configuradas.
 * @property {number} [available_rooms] - Habitaciones disponibles para configurar (detalle).
 */

/**
 * @typedef {Object} HotelPayload
 * @property {string} name - Nombre comercial del hotel.
 * @property {string} address - Dirección física del hotel.
 * @property {number} city_id - Identificador de la ciudad.
 * @property {string} nit - Número de identificación tributaria.
 * @property {number} max_rooms - Capacidad máxima de habitaciones.
 */

/**
 * @typedef {Object} RoomConfiguration
 * @property {number} id - Identificador único de la configuración.
 * @property {number} hotel_id - Identificador del hotel al que pertenece.
 * @property {number} room_type_id - Identificador del tipo de habitación.
 * @property {string} room_type_name - Nombre del tipo de habitación.
 * @property {number} accommodation_id - Identificador de la acomodación.
 * @property {string} accommodation_name - Nombre de la acomodación.
 * @property {number} quantity - Cantidad de habitaciones de esta configuración.
 */

/**
 * @typedef {Object} RoomConfigurationPayload
 * @property {number} room_type_id - Identificador del tipo de habitación.
 * @property {number} accommodation_id - Identificador de la acomodación.
 * @property {number} quantity - Cantidad de habitaciones a registrar.
 */

/**
 * Obtiene el listado de ciudades disponibles en el catálogo.
 *
 * @returns {Promise<City[]>} Lista de ciudades.
 * @throws {import('axios').AxiosError} Si la petición falla (red, 4xx o 5xx).
 */
export const fetchCities = () => api.get('/cities').then((r) => r.data.data);

/**
 * Obtiene el listado de tipos de habitación disponibles en el catálogo.
 *
 * @returns {Promise<RoomType[]>} Lista de tipos de habitación.
 * @throws {import('axios').AxiosError} Si la petición falla (red, 4xx o 5xx).
 */
export const fetchRoomTypes = () => api.get('/room-types').then((r) => r.data.data);

/**
 * Obtiene las acomodaciones válidas para un tipo de habitación dado.
 *
 * @param {number|string} roomTypeId - Identificador del tipo de habitación.
 * @returns {Promise<Accommodation[]>} Lista de acomodaciones compatibles.
 * @throws {import('axios').AxiosError} Si la petición falla (red, 4xx o 5xx).
 */
export const fetchAccommodationsByRoomType = (roomTypeId) =>
    api.get(`/room-types/${roomTypeId}/accommodations`).then((r) => r.data.data);

/**
 * Obtiene el listado de todos los hoteles registrados.
 *
 * @returns {Promise<Hotel[]>} Lista de hoteles con resumen de habitaciones configuradas.
 * @throws {import('axios').AxiosError} Si la petición falla (red, 4xx o 5xx).
 */
export const fetchHotels = () => api.get('/hotels').then((r) => r.data.data);

/**
 * Obtiene el detalle de un hotel por su identificador.
 *
 * @param {number|string} id - Identificador del hotel.
 * @returns {Promise<Hotel>} Datos completos del hotel.
 * @throws {import('axios').AxiosError} Si la petición falla (404 si no existe, u otros errores).
 */
export const fetchHotel = (id) => api.get(`/hotels/${id}`).then((r) => r.data.data);

/**
 * Crea un nuevo hotel en el sistema.
 *
 * @param {HotelPayload} data - Datos del hotel a registrar.
 * @returns {Promise<Hotel>} Hotel creado con su identificador asignado.
 * @throws {import('axios').AxiosError} Si la validación falla (422) o hay otro error de servidor.
 */
export const createHotel = (data) => api.post('/hotels', data).then((r) => r.data.data);

/**
 * Actualiza los datos de un hotel existente.
 *
 * @param {number|string} id - Identificador del hotel a modificar.
 * @param {HotelPayload} data - Datos actualizados del hotel.
 * @returns {Promise<Hotel>} Hotel actualizado.
 * @throws {import('axios').AxiosError} Si el hotel no existe (404), la validación falla (422) u otro error.
 */
export const updateHotel = (id, data) => api.put(`/hotels/${id}`, data).then((r) => r.data.data);

/**
 * Elimina un hotel del sistema.
 *
 * @param {number|string} id - Identificador del hotel a eliminar.
 * @returns {Promise<import('axios').AxiosResponse>} Respuesta HTTP de axios (sin cuerpo de datos).
 * @throws {import('axios').AxiosError} Si el hotel no existe (404) o hay otro error de servidor.
 */
export const deleteHotel = (id) => api.delete(`/hotels/${id}`);

/**
 * Obtiene las configuraciones de habitación de un hotel.
 *
 * @param {number|string} hotelId - Identificador del hotel.
 * @returns {Promise<RoomConfiguration[]>} Lista de configuraciones de habitación.
 * @throws {import('axios').AxiosError} Si la petición falla (404 si el hotel no existe, u otros errores).
 */
export const fetchRoomConfigurations = (hotelId) =>
    api.get(`/hotels/${hotelId}/room-configurations`).then((r) => r.data.data);

/**
 * Crea una nueva configuración de habitación para un hotel.
 *
 * @param {number|string} hotelId - Identificador del hotel.
 * @param {RoomConfigurationPayload} data - Datos de la configuración a registrar.
 * @returns {Promise<RoomConfiguration>} Configuración creada.
 * @throws {import('axios').AxiosError} Si la validación falla (422), se excede el límite de habitaciones u otro error.
 */
export const createRoomConfiguration = (hotelId, data) =>
    api.post(`/hotels/${hotelId}/room-configurations`, data).then((r) => r.data.data);

/**
 * Actualiza una configuración de habitación existente.
 *
 * @param {number|string} hotelId - Identificador del hotel.
 * @param {number|string} configId - Identificador de la configuración a modificar.
 * @param {RoomConfigurationPayload} data - Datos actualizados de la configuración.
 * @returns {Promise<RoomConfiguration>} Configuración actualizada.
 * @throws {import('axios').AxiosError} Si no existe (404), la validación falla (422) u otro error.
 */
export const updateRoomConfiguration = (hotelId, configId, data) =>
    api.put(`/hotels/${hotelId}/room-configurations/${configId}`, data).then((r) => r.data.data);

/**
 * Elimina una configuración de habitación de un hotel.
 *
 * @param {number|string} hotelId - Identificador del hotel.
 * @param {number|string} configId - Identificador de la configuración a eliminar.
 * @returns {Promise<import('axios').AxiosResponse>} Respuesta HTTP de axios (sin cuerpo de datos).
 * @throws {import('axios').AxiosError} Si no existe (404) o hay otro error de servidor.
 */
export const deleteRoomConfiguration = (hotelId, configId) =>
    api.delete(`/hotels/${hotelId}/room-configurations/${configId}`);
