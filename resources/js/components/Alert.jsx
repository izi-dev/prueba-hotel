/**
 * Muestra un mensaje de alerta contextual (error o éxito) con opción de cierre.
 * No renderiza nada si no se proporciona mensaje.
 *
 * @param {Object} props - Propiedades del componente.
 * @param {'error'|'success'} [props.type='error'] - Variante visual de la alerta.
 * @param {string} [props.message] - Texto del mensaje a mostrar. Si está vacío, no se renderiza.
 * @param {() => void} [props.onClose] - Callback invocado al pulsar el botón "Cerrar".
 * @returns {import('react').ReactElement|null} Alerta estilizada o null si no hay mensaje.
 */
export default function Alert({ type = 'error', message, onClose }) {
    if (!message) return null;

    const styles = {
        error: 'bg-red-50 text-red-800 border-red-200',
        success: 'bg-green-50 text-green-800 border-green-200',
    };

    return (
        <div className={`mb-4 rounded-md border px-4 py-3 text-sm ${styles[type]}`}>
            <div className="flex items-start justify-between gap-3">
                <span>{message}</span>
                {onClose && (
                    <button type="button" onClick={onClose} className="font-medium hover:underline">
                        Cerrar
                    </button>
                )}
            </div>
        </div>
    );
}
