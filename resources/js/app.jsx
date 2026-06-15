import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route, Link } from 'react-router-dom';
import HotelListPage from './pages/HotelListPage';
import HotelFormPage from './pages/HotelFormPage';
import HotelDetailPage from './pages/HotelDetailPage';

/**
 * Componente raíz de la aplicación de gestión hotelera.
 * Montada bajo el prefijo `/app` en el enrutador del servidor.
 */
function App() {
    return (
        <div className="min-h-screen">
            <header className="bg-indigo-700 text-white shadow">
                <div className="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
                    <div className="flex items-center gap-4">
                        <Link to="/" className="text-lg font-semibold tracking-tight">
                            Gestión Hotelera
                        </Link>
                        <a
                            href="/"
                            className="hidden text-sm text-indigo-200 hover:text-white sm:inline"
                        >
                            Inicio
                        </a>
                    </div>
                    <Link
                        to="/hoteles/nuevo"
                        className="rounded-md bg-white px-3 py-1.5 text-sm font-medium text-indigo-700 hover:bg-indigo-50"
                    >
                        Nuevo hotel
                    </Link>
                </div>
            </header>
            <main className="mx-auto max-w-6xl px-4 py-6">
                <Routes>
                    <Route path="/" element={<HotelListPage />} />
                    <Route path="/hoteles/nuevo" element={<HotelFormPage />} />
                    <Route path="/hoteles/:id" element={<HotelDetailPage />} />
                    <Route path="/hoteles/:id/editar" element={<HotelFormPage />} />
                </Routes>
            </main>
        </div>
    );
}

const rootElement = document.getElementById('app');

if (rootElement) {
    createRoot(rootElement).render(
        <StrictMode>
            <BrowserRouter basename="/app">
                <App />
            </BrowserRouter>
        </StrictMode>,
    );
}
