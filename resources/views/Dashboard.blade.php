<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | CodeVision</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">

    <!-- SCRIPT DROPDOWN -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("user-toggle");
            const menu = document.getElementById("user-menu");

            toggle.addEventListener("click", () => {
                toggle.classList.toggle("active");
                menu.classList.toggle("show");
            });

            document.addEventListener("click", (e) => {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    toggle.classList.remove("active");
                    menu.classList.remove("show");
                }
            });
        });
    </script>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-left">
            <img src="../images/logo.png" class="logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ $user->name }} <!-- Mostrar nombre del usuario desde la base de datos -->
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9.5L12 3l9 6.5V21H3z" />
                    </svg>
                    Inicio
                </a>

                <a href="{{ route('profile.edit') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
                    </svg>
                    Perfil
                </a>

                <!-- Formulario de Logout -->
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <a href="#" class="btn-search" onclick="this.closest('form').submit();" style="color: black; background-color: #FFFFFF; padding: 12px 18px; text-decoration: none; border-radius: 10px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Cerrar sesión
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <h2 class="hero-title">¡Bienvenido!</h2>
    </section>

    <!-- BUSCADOR -->
    <section class="search-section">
        <div class="search-box">
            <input type="text" placeholder="Buscar Eventos, Categorías o Tecnologías..." />
            <button class="btn-search">Buscar</button>
        </div>

        <div class="filters">
            <select>
                <option>Todas las categorías</option>
            </select>
            <select>
                <option>Cualquier fecha</option>
            </select>

            <div class="new-event" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('events.create') }}" class="btn-search" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    + Crear Evento
                </a>

                <a href="{{ route('teams.create') }}" class="btn-search" style="text-decoration: none; background-color: #2d3748; display: flex; align-items: center; justify-content: center;">
                    👥 Crear Equipo
                </a>

                <a href="{{ route('teams.join') }}" class="btn-search" style="text-decoration: none; background-color: #4a5568; display: flex; align-items: center; justify-content: center;">
                    🔗 Unirse
                </a>

                <a href="{{ route('teams.index') }}" class="btn-search" style="text-decoration: none; background-color: #3182ce; display: flex; align-items: center; justify-content: center;">
                    📋 Mis Equipos
                </a>
            </div>
        </div>
    </section>

    <!-- EVENTOS -->
    <section class="events">
        <div class="events-header">
            <h2>Eventos y concursos de tecnología</h2>
            <a href="#" class="view-all">Ver todos los eventos →</a>
        </div>

        <div class="events-grid">
    @if($events->count() > 0)
        @foreach($events as $event)
            <div class="event-card">
                <!-- Redirigir al hacer clic en la tarjeta -->
                <a href="{{ route('informacion.evento') }}" class="event-link">
                    <!-- Aquí va la imagen del evento -->
                    <img src="{{ $event->image_url ?? asset('images/default-event.jpg') }}" class="event-img" alt="{{ $event->title }}" onerror="this.src='{{ asset('images/logo.png') }}'"> 
                    
                    <!-- Aquí va la información del evento -->
                    <div class="event-info">
                        <p class="event-date">
                            📅 {{ \Carbon\Carbon::parse($event->start_date)->format('d M, Y') }} 
                            - {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} hrs
                        </p>

                        <h3 class="event-title">{{ $event->title }}</h3>

                        <p class="event-description">
                            {{ \Illuminate\Support\Str::limit($event->description, 100) }}
                        </p>

                        <p class="event-location">📍 {{ $event->location }}</p>
                        
                        <p style="font-size: 0.8rem; color: #666; margin-top: 5px;">
                            Organiza: {{ $event->organizer }}
                        </p>
                    </div>
                </a> <!-- Cierre del enlace -->
            </div>
        @endforeach
    @else
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
            <h3>No hay eventos próximos 😢</h3>
            <p>¡Sé el primero en crear uno!</p>
        </div>
    @endif
</div>



             
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca para gestión de eventos tecnológicos.</p>
            </div>

            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li>Inicio</li>
                    <li>Eventos</li>
                    <li>Categorías</li>
                    <li>Calendario</li>
                </ul>
            </div>

            <div>
                <h3>Recursos</h3>
                <ul>
                    <li>Preguntas frecuentes</li>
                    <li>Cómo inscribirse</li>
                    <li>Políticas de evento</li>
                </ul>
            </div>

            <div>
                <h3>Contactos</h3>
                <ul>
                    <li>Inicio</li>
                    <li>Eventos</li>
                    <li>Categorías</li>
                </ul>
            </div>
        </div>

        <p class="footer-copy">© 2023 CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>

</html>
