import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { deleteHotel, fetchHotels } from '../api/hotels';
import Alert from '../components/Alert';
import LoadingSpinner from '../components/LoadingSpinner';

/**
 * Página de listado de hoteles.
 * Muestra una tabla con los hoteles registrados y permite ver, editar o eliminar cada uno.
 * No recibe props; obtiene los datos directamente de la API al montarse.
 *
 * @returns {import('react').ReactElement} Tabla de hoteles o indicador de carga.
 */
export default function HotelListPage() {
    const [hotels, setHotels] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    /**
     * Carga el listado de hoteles desde la API y actualiza el estado local.
     *
     * @returns {Promise<void>}
     */
    const loadHotels = async () => {
        try {
            setLoading(true);
            setError('');
            setHotels(await fetchHotels());
        } catch {
            setError('No se pudieron cargar los hoteles.');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadHotels();
    }, []);

    /**
     * Elimina un hotel tras confirmación del usuario y recarga el listado.
     *
     * @param {number} id - Identificador del hotel a eliminar.
     * @param {string} name - Nombre del hotel (se muestra en el diálogo de confirmación).
     * @returns {Promise<void>}
     */
    const handleDelete = async (id, name) => {
        if (!window.confirm(`¿Eliminar el hotel "${name}"?`)) return;

        try {
            await deleteHotel(id);
            await loadHotels();
        } catch {
            setError('No se pudo eliminar el hotel.');
        }
    };

    if (loading) return <LoadingSpinner />;

    return (
        <div>
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-slate-800">Hoteles</h1>
                <p className="mt-1 text-sm text-slate-600">
                    Administre los hoteles de la compañía y sus configuraciones de habitación.
                </p>
            </div>

            <Alert message={error} onClose={() => setError('')} />

            {hotels.length === 0 ? (
                <div className="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                    No hay hoteles registrados.{' '}
                    <Link to="/hoteles/nuevo" className="font-medium text-indigo-600 hover:underline">
                        Crear el primero
                    </Link>
                </div>
            ) : (
                <div className="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
                    <table className="min-w-full text-left text-sm">
                        <thead className="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th className="px-4 py-3">Nombre</th>
                                <th className="px-4 py-3">Ciudad</th>
                                <th className="px-4 py-3">NIT</th>
                                <th className="px-4 py-3">Habitaciones</th>
                                <th className="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {hotels.map((hotel) => (
                                <tr key={hotel.id} className="border-t border-slate-100">
                                    <td className="px-4 py-3 font-medium">{hotel.name}</td>
                                    <td className="px-4 py-3">{hotel.city_name}</td>
                                    <td className="px-4 py-3">{hotel.nit}</td>
                                    <td className="px-4 py-3">
                                        {hotel.configured_rooms} / {hotel.max_rooms}
                                    </td>
                                    <td className="px-4 py-3 text-right">
                                        <div className="flex justify-end gap-2">
                                            <Link
                                                to={`/hoteles/${hotel.id}`}
                                                className="rounded px-2 py-1 text-indigo-600 hover:bg-indigo-50"
                                            >
                                                Ver
                                            </Link>
                                            <Link
                                                to={`/hoteles/${hotel.id}/editar`}
                                                className="rounded px-2 py-1 text-slate-600 hover:bg-slate-100"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                type="button"
                                                onClick={() => handleDelete(hotel.id, hotel.name)}
                                                className="rounded px-2 py-1 text-red-600 hover:bg-red-50"
                                            >
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
        </div>
    );
}
