<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | CodeVision</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="icon" type="../image/png" href="images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- SCRIPT DROPDOWN (Mantenido) -->
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
    <style>
        /* Estilos básicos para los botones para que se vean organizados */
        .new-event-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 15px;
            padding: 10px 0;
            border-top: 1px solid #eee;
        }
        .btn-control {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-search {
            background-color: #3182ce; /* Azul primario */
            color: white;
        }
        .btn-edit {
            background-color: #acce31; /* Verde/Amarillo */
            color: #333;
        }
        .btn-teams {
            background-color: #4a5568; /* Gris oscuro */
            color: white;
        }
        .btn-join {
             background-color: #2d3748; /* Gris más oscuro */
            color: white;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-left">
            <!-- LOGO -->
            <img src="../images/logo.png" class="logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">
            <!-- NOMBRE DEL USUARIO -->
            <div id="user-toggle" class="user-name">
                {{ $user->name }} <!-- Mostrar nombre del usuario desde la base de datos -->

                <!-- FLECHITA -->
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <!-- MENU -->
            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard') }}">
                    <!-- Iconos SVG omitidos para brevedad -->
                    Inicio
                </a>

                <a href="{{ route('profile.edit') }}"> <!-- Enlace actualizado al perfil -->
                    <!-- Iconos SVG omitidos para brevedad -->
                    Perfil
                </a>
                
                <!-- Formulario de Logout -->
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf 
                    <a href="#" class="btn-search" onclick="this.closest('form').submit();" style="color: black; background-color: #FFFFFF; padding: 12px 18px; text-decoration: none; border-radius: 10px;">
                        <!-- Iconos SVG omitidos para brevedad -->
                        Cerrar sesión
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <h2 class="hero-title">¡Bienvenido, {{ $user->name }}!</h2>
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

            {{-- ************************************************* --}}
            {{-- PANEL DE ACCIONES PROTEGIDO POR ROLES Y PERMISOS --}}
            {{-- ************************************************* --}}
            <div class="new-event-controls">
                
                {{-- 1. BOTONES DE ADMINISTRACIÓN (Solo para quien pueda crear eventos) --}}
                @can('crear eventos')
                    <a href="{{ route('events.create') }}" class="btn-control btn-search">
                        + Crear Evento
                    </a>
                    
                    <a href="{{ route('events.edit.last') }}" class="btn-control btn-edit">
                        👌 Editar Último Evento
                    </a>
                @endcan

                {{-- 2. BOTONES DE GESTIÓN DE EQUIPOS (Para cualquier usuario que pueda participar) --}}
                
                {{-- ELIMINADO: BOTÓN CRUCIAL: Mis Equipos (Teams.index) --}}

                {{-- Creación de Equipo (accesible por todos los roles que pueden participar) --}}
                <a href="{{ route('teams.create') }}" class="btn-control btn-teams">
                    👥 Crear Equipo
                </a>

                {{-- Unirse a Equipo --}}
                <a href="{{ route('teams.join') }}" class="btn-control btn-join">
                    🔗 Unirse a Equipo
                </a>
                
                {{-- 3. BOTÓN DE CALIFICACIÓN (Solo para el Juez) --}}
                @can('calificar')
                    <a href="{{ route('teams.calificar') }}" class="btn-control btn-edit" style="background-color: #f6ad55;">
                        ⭐ Interfaz de Calificación
                    </a>
                @endcan

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
                    {{-- CORRECCIÓN: HACEMOS LA TARJETA CLICKABLE A LA VISTA DE DETALLES --}}
                    <a href="{{ route('events.show', $event->id) }}" class="card-link"></a> 

                    <img src="{{ $event->image_url ?? asset('images/default-event.jpg') }}" 
                         class="event-img" 
                         alt="{{ $event->title }}"
                         onerror="this.src='{{ asset('images/logo.png') }}'"> 
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
                    <li>Términos y condiciones</li>
                </ul>
            </div>

            <div>
                <h3>Contactos</h3>
                <ul>
                    <li>Soporte</li>
                    <li>Informes</li>
                </ul>
            </div>

        </div>

        <p class="footer-copy">© 2023 CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>

</html>
