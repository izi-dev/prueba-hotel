import { useCallback, useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import {
    createRoomConfiguration,
    deleteRoomConfiguration,
    fetchAccommodationsByRoomType,
    fetchHotel,
    fetchRoomConfigurations,
    fetchRoomTypes,
    updateRoomConfiguration,
} from '../api/hotels';
import Alert from '../components/Alert';
import LoadingSpinner from '../components/LoadingSpinner';

/** @type {{ room_type_id: string, accommodation_id: string, quantity: string }} */
const emptyConfigForm = {
    room_type_id: '',
    accommodation_id: '',
    quantity: '',
};

/**
 * Página de detalle de un hotel con gestión de configuraciones de habitación.
 * Permite consultar datos del hotel, agregar, editar y eliminar configuraciones.
 * No recibe props; obtiene el identificador del hotel desde el parámetro de ruta `:id`.
 *
 * @returns {import('react').ReactElement} Vista de detalle del hotel o indicador de carga.
 */
export default function HotelDetailPage() {
    const { id } = useParams();
    const [hotel, setHotel] = useState(null);
    const [configurations, setConfigurations] = useState([]);
    const [roomTypes, setRoomTypes] = useState([]);
    const [accommodations, setAccommodations] = useState([]);
    const [form, setForm] = useState(emptyConfigForm);
    const [editingId, setEditingId] = useState(null);
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

    /**
     * Carga el hotel, sus configuraciones y el catálogo de tipos de habitación.
     *
     * @returns {Promise<void>}
     */
    const loadData = useCallback(async () => {
        try {
            setLoading(true);
            setError('');
            const [hotelData, configs, types] = await Promise.all([
                fetchHotel(id),
                fetchRoomConfigurations(id),
                fetchRoomTypes(),
            ]);
            setHotel(hotelData);
            setConfigurations(configs);
            setRoomTypes(types);
        } catch {
            setError('No se pudo cargar el detalle del hotel.');
        } finally {
            setLoading(false);
        }
    }, [id]);

    useEffect(() => {
        loadData();
    }, [loadData]);

    useEffect(() => {
        const loadAccommodations = async () => {
            if (!form.room_type_id) {
                setAccommodations([]);
                return;
            }

            try {
                setAccommodations(await fetchAccommodationsByRoomType(form.room_type_id));
            } catch {
                setAccommodations([]);
            }
        };

        loadAccommodations();
    }, [form.room_type_id]);

    /**
     * Actualiza un campo del formulario de configuración.
     * Al cambiar el tipo de habitación, reinicia la acomodación seleccionada.
     *
     * @param {import('react').ChangeEvent<HTMLInputElement|HTMLSelectElement>} event - Evento de cambio del campo.
     */
    const handleChange = (event) => {
        const { name, value } = event.target;
        setForm((current) => ({
            ...current,
            [name]: value,
            ...(name === 'room_type_id' ? { accommodation_id: '' } : {}),
        }));
    };

    /**
     * Restablece el formulario de configuración al estado inicial y cancela la edición.
     */
    const resetForm = () => {
        setForm(emptyConfigForm);
        setEditingId(null);
    };

    /**
     * Crea o actualiza una configuración de habitación según el modo de edición activo.
     *
     * @param {import('react').FormEvent<HTMLFormElement>} event - Evento de envío del formulario.
     * @returns {Promise<void>}
     */
    const handleSubmit = async (event) => {
        event.preventDefault();
        setSaving(true);
        setError('');

        const payload = {
            room_type_id: Number(form.room_type_id),
            accommodation_id: Number(form.accommodation_id),
            quantity: Number(form.quantity),
        };

        try {
            if (editingId) {
                await updateRoomConfiguration(id, editingId, payload);
            } else {
                await createRoomConfiguration(id, payload);
            }
            resetForm();
            await loadData();
        } catch (err) {
            const message =
                err.response?.data?.message ||
                Object.values(err.response?.data?.errors || {}).flat().join(' ') ||
                'No se pudo guardar la configuración.';
            setError(message);
        } finally {
            setSaving(false);
        }
    };

    /**
     * Carga los datos de una configuración existente en el formulario para editarla.
     *
     * @param {import('../api/hotels').RoomConfiguration} config - Configuración a editar.
     */
    const handleEdit = (config) => {
        setEditingId(config.id);
        setForm({
            room_type_id: String(config.room_type_id),
            accommodation_id: String(config.accommodation_id),
            quantity: String(config.quantity),
        });
    };

    /**
     * Elimina una configuración de habitación tras confirmación del usuario.
     *
     * @param {number} configId - Identificador de la configuración a eliminar.
     * @returns {Promise<void>}
     */
    const handleDelete = async (configId) => {
        if (!window.confirm('¿Eliminar esta configuración?')) return;

        try {
            await deleteRoomConfiguration(id, configId);
            await loadData();
        } catch {
            setError('No se pudo eliminar la configuración.');
        }
    };

    if (loading) return <LoadingSpinner />;
    if (!hotel) return <Alert message={error || 'Hotel no encontrado.'} />;

    const availableRooms = hotel.max_rooms - hotel.configured_rooms;

    return (
        <div>
            <div className="mb-6">
                <Link to="/" className="text-sm text-indigo-600 hover:underline">
                    ← Volver al listado
                </Link>
                <div className="mt-2 flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h1 className="text-2xl font-bold text-slate-800">{hotel.name}</h1>
                        <p className="mt-1 text-sm text-slate-600">{hotel.address}</p>
                    </div>
                    <Link
                        to={`/hoteles/${id}/editar`}
                        className="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-medium hover:bg-slate-50"
                    >
                        Editar hotel
                    </Link>
                </div>
            </div>

            <Alert message={error} onClose={() => setError('')} />

            <div className="mb-6 grid gap-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p className="text-xs uppercase text-slate-500">Ciudad</p>
                    <p className="font-medium">{hotel.city_name}</p>
                </div>
                <div>
                    <p className="text-xs uppercase text-slate-500">NIT</p>
                    <p className="font-medium">{hotel.nit}</p>
                </div>
                <div>
                    <p className="text-xs uppercase text-slate-500">Configuradas</p>
                    <p className="font-medium">
                        {hotel.configured_rooms} / {hotel.max_rooms}
                    </p>
                </div>
                <div>
                    <p className="text-xs uppercase text-slate-500">Disponibles</p>
                    <p className="font-medium">{availableRooms}</p>
                </div>
            </div>

            <div className="mb-6 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 className="mb-4 text-lg font-semibold">
                    {editingId ? 'Editar configuración' : 'Agregar configuración de habitación'}
                </h2>
                <form onSubmit={handleSubmit} className="grid gap-4 md:grid-cols-4">
                    <div>
                        <label className="mb-1 block text-sm font-medium">Tipo</label>
                        <select
                            name="room_type_id"
                            value={form.room_type_id}
                            onChange={handleChange}
                            required
                            className="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                        >
                            <option value="">Seleccione...</option>
                            {roomTypes.map((type) => (
                                <option key={type.id} value={type.id}>
                                    {type.name}
                                </option>
                            ))}
                        </select>
                    </div>
                    <div>
                        <label className="mb-1 block text-sm font-medium">Acomodación</label>
                        <select
                            name="accommodation_id"
                            value={form.accommodation_id}
                            onChange={handleChange}
                            required
                            disabled={!form.room_type_id}
                            className="w-full rounded-md border border-slate-300 px-3 py-2 text-sm disabled:bg-slate-100"
                        >
                            <option value="">Seleccione...</option>
                            {accommodations.map((item) => (
                                <option key={item.id} value={item.id}>
                                    {item.name}
                                </option>
                            ))}
                        </select>
                    </div>
                    <div>
                        <label className="mb-1 block text-sm font-medium">Cantidad</label>
                        <input
                            name="quantity"
                            type="number"
                            min="1"
                            value={form.quantity}
                            onChange={handleChange}
                            required
                            className="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                        />
                    </div>
                    <div className="flex items-end gap-2">
                        <button
                            type="submit"
                            disabled={saving}
                            className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                        >
                            {saving ? 'Guardando...' : editingId ? 'Actualizar' : 'Agregar'}
                        </button>
                        {editingId && (
                            <button
                                type="button"
                                onClick={resetForm}
                                className="rounded-md border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50"
                            >
                                Cancelar
                            </button>
                        )}
                    </div>
                </form>
            </div>

            <div className="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
                <table className="min-w-full text-left text-sm">
                    <thead className="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th className="px-4 py-3">Cantidad</th>
                            <th className="px-4 py-3">Tipo habitación</th>
                            <th className="px-4 py-3">Acomodación</th>
                            <th className="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {configurations.length === 0 ? (
                            <tr>
                                <td colSpan="4" className="px-4 py-6 text-center text-slate-500">
                                    Sin configuraciones registradas.
                                </td>
                            </tr>
                        ) : (
                            configurations.map((config) => (
                                <tr key={config.id} className="border-t border-slate-100">
                                    <td className="px-4 py-3 font-medium">{config.quantity}</td>
                                    <td className="px-4 py-3">{config.room_type_name}</td>
                                    <td className="px-4 py-3">{config.accommodation_name}</td>
                                    <td className="px-4 py-3 text-right">
                                        <div className="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                onClick={() => handleEdit(config)}
                                                className="rounded px-2 py-1 text-slate-600 hover:bg-slate-100"
                                            >
                                                Editar
                                            </button>
                                            <button
                                                type="button"
                                                onClick={() => handleDelete(config.id)}
                                                className="rounded px-2 py-1 text-red-600 hover:bg-red-50"
                                            >
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
