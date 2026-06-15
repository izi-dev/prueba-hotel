/**
 * Indicador visual de carga centrado.
 * Se utiliza mientras se esperan datos de la API antes de renderizar el contenido principal.
 *
 * @returns {import('react').ReactElement} Spinner animado de carga.
 */
export default function LoadingSpinner() {
    return (
        <div className="flex items-center justify-center py-12">
            <div className="h-8 w-8 animate-spin rounded-full border-4 border-indigo-200 border-t-indigo-600" />
        </div>
    );
}
