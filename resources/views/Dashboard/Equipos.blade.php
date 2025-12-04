<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mis Equipos | CodeVision</title>
    <link rel="stylesheet" href="{{ asset('styles/equipos.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
</head>

<!-- SCRIPT DROPDOWN -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const toggle = document.getElementById("user-toggle");
        const menu = document.getElementById("user-menu");

        toggle.addEventListener("click", () => {
            menu.classList.toggle("show");
        });

        document.addEventListener("click", (e) => {
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove("show");
            }
        });
    });
</script>

<style>
    /* Estilos para el menú desplegable */
    .dropdown {
        display: none;
        position: absolute;
        top: 60px; /* Ajusta para que esté justo debajo del nombre */
        right: 70px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 100;
        width: 200px;
        padding: 10px;
    }

    .dropdown.show {
        display: block;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Estilo para el botón de usuario */
    #user-toggle {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    #user-toggle.active svg {
        transform: rotate(180deg);
        transition: transform 0.3s;
    }

    .arrow {
        transition: transform 0.3s;
    }
</style>


<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="site-title">CodeVision</span>
        </div>
        <!-- NOMBRE DEL USUARIO -->
<div id="user-toggle" class="user-name">
    {{ auth()->user()->name }} <!-- Mostrar nombre del usuario desde la base de datos -->

    <!-- FLECHITA -->
    <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <polyline points="6 9 12 15 18 9" />
    </svg>
</div>

<!-- MENU -->
<div id="user-menu" class="dropdown">
    <a href="{{ route('dashboard') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M3 9.5L12 3l9 6.5V21H3z" />
        </svg>
        Inicio
    </a>

    <a href="{{ route('profile.edit') }}"> <!-- Enlace actualizado al perfil -->
        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <circle cx="12" cy="7" r="4" />
            <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
        </svg>
        Perfil
    </a>

    <!-- Formulario de Logout -->
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf  <!-- Asegura que la solicitud sea segura con un token CSRF -->
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
    </nav>

    <h1 id="tituloRol">Mis Equipos Inscritos</h1>

    <table id="tablaEquipos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Equipo</th>
                <th>Rol</th>
                <th>Líder</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allTeams as $team)
                <tr>
                    <td>{{ $team->id }}</td>
                    <td>
                        <strong>{{ $team->name }}</strong>
                        <br>
                        <small style="color: gray;">{{ $team->leader_career }}</small>
                    </td>
                    <td>
                        @if($team->user_id == Auth::id())
                            <span style="background-color: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 10px; font-size: 0.8em;">Líder</span>
                        @else
                            <span style="background-color: #e0f2fe; color: #075985; padding: 2px 8px; border-radius: 10px; font-size: 0.8em;">Miembro</span>
                        @endif
                    </td>
                    <td>{{ $team->leader_name }}</td>
                    <td>
                        <button onclick="alert('Aquí podrías ver detalles del equipo {{ $team->name }}')">
                            Ver Detalles
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        <p>No perteneces a ningún equipo aún.</p>
                        <div style="margin-top: 10px;">
                            <a href="{{ route('teams.create') }}" style="color: blue; margin-right: 15px;">Crear uno</a>
                            <span>o</span>
                            <a href="{{ route('teams.join') }}" style="color: blue; margin-left: 15px;">Unirse a uno</a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca.</p>
            </div>
            </div>
        <p class="footer-copy">© 2025 CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

    <script>
        // Ya no necesitamos la lógica compleja de renderizado JS
        // porque Laravel ya dibujó la tabla con los datos reales arriba.
        
        // Mantenemos solo el menú dropdown si lo usas
        document.addEventListener("DOMContentLoaded", () => {
             // Lógica visual simple si la necesitas
        });
    </script>

</body>
</html>
