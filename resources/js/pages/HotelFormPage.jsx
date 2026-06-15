import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { createHotel, fetchCities, fetchHotel, updateHotel } from '../api/hotels';
import Alert from '../components/Alert';
import LoadingSpinner from '../components/LoadingSpinner';

/** @type {{ name: string, address: string, city_id: string, nit: string, max_rooms: string }} */
const emptyForm = {
    name: '',
    address: '',
    city_id: '',
    nit: '',
    max_rooms: '',
};

/**
 * Página de formulario para crear o editar un hotel.
 * Detecta el modo de edición según el parámetro de ruta `:id`.
 * No recibe props; utiliza `useParams` y `useNavigate` de React Router.
 *
 * @returns {import('react').ReactElement} Formulario de hotel o indicador de carga.
 */
export default function HotelFormPage() {
    const { id } = useParams();
    const navigate = useNavigate();
    const isEditing = Boolean(id);

    const [form, setForm] = useState(emptyForm);
    const [cities, setCities] = useState([]);
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

    useEffect(() => {
        const load = async () => {
            try {
                const citiesData = await fetchCities();
                setCities(citiesData);

                if (isEditing) {
                    const hotel = await fetchHotel(id);
                    setForm({
                        name: hotel.name,
                        address: hotel.address,
                        city_id: String(hotel.city_id),
                        nit: hotel.nit,
                        max_rooms: String(hotel.max_rooms),
                    });
                }
            } catch {
                setError('No se pudo cargar el formulario.');
            } finally {
                setLoading(false);
            }
        };

        load();
    }, [id, isEditing]);

    /**
     * Actualiza un campo del formulario al cambiar un input o select.
     *
     * @param {import('react').ChangeEvent<HTMLInputElement|HTMLSelectElement>} event - Evento de cambio del campo.
     */
    const handleChange = (event) => {
        const { name, value } = event.target;
        setForm((current) => ({ ...current, [name]: value }));
    };

    /**
     * Envía el formulario para crear o actualizar el hotel y redirige al detalle.
     *
     * @param {import('react').FormEvent<HTMLFormElement>} event - Evento de envío del formulario.
     * @returns {Promise<void>}
     */
    const handleSubmit = async (event) => {
        event.preventDefault();
        setSaving(true);
        setError('');

        const payload = {
            name: form.name,
            address: form.address,
            city_id: Number(form.city_id),
            nit: form.nit,
            max_rooms: Number(form.max_rooms),
        };

        try {
            const hotel = isEditing
                ? await updateHotel(id, payload)
                : await createHotel(payload);

            navigate(`/hoteles/${hotel.id}`);
        } catch (err) {
            const message =
                err.response?.data?.message ||
                Object.values(err.response?.data?.errors || {}).flat().join(' ') ||
                'No se pudo guardar el hotel.';
            setError(message);
        } finally {
            setSaving(false);
        }
    };

    if (loading) return <LoadingSpinner />;

    return (
        <div className="max-w-2xl">
            <div className="mb-6">
                <Link to="/" className="text-sm text-indigo-600 hover:underline">
                    ← Volver al listado
                </Link>
                <h1 className="mt-2 text-2xl font-bold text-slate-800">
                    {isEditing ? 'Editar hotel' : 'Nuevo hotel'}
                </h1>
            </div>

            <Alert message={error} onClose={() => setError('')} />

            <form onSubmit={handleSubmit} className="space-y-4 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div>
                    <label className="mb-1 block text-sm font-medium">Nombre</label>
                    <input
                        name="name"
                        value={form.name}
                        onChange={handleChange}
                        required
                        className="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                    />
                </div>
                <div>
                    <label className="mb-1 block text-sm font-medium">Dirección</label>
                    <input
                        name="address"
                        value={form.address}
                        onChange={handleChange}
                        required
                        className="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                    />
                </div>
                <div className="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label className="mb-1 block text-sm font-medium">Ciudad</label>
                        <select
                            name="city_id"
                            value={form.city_id}
                            onChange={handleChange}
                            required
                            className="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                        >
                            <option value="">Seleccione...</option>
                            {cities.map((city) => (
                                <option key={city.id} value={city.id}>
                                    {city.name}
                                </option>
                            ))}
                        </select>
                    </div>
                    <div>
                        <label className="mb-1 block text-sm font-medium">NIT</label>
                        <input
                            name="nit"
                            value={form.nit}
                            onChange={handleChange}
                            placeholder="12345678-9"
                            required
                            className="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                        />
                    </div>
                </div>
                <div>
                    <label className="mb-1 block text-sm font-medium">Número máximo de habitaciones</label>
                    <input
                        name="max_rooms"
                        type="number"
                        min="1"
                        value={form.max_rooms}
                        onChange={handleChange}
                        required
                        className="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                    />
                </div>
                <div className="flex gap-3 pt-2">
                    <button
                        type="submit"
                        disabled={saving}
                        className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                    >
                        {saving ? 'Guardando...' : 'Guardar'}
                    </button>
                    <Link
                        to={isEditing ? `/hoteles/${id}` : '/'}
                        className="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Cancelar
                    </Link>
                </div>
            </form>
        </div>
    );
}
