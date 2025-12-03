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

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision</span>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }}
            </div>
            <a href="{{ route('dashboard') }}" style="margin-left: 15px; color: white; text-decoration: none; border: 1px solid white; padding: 5px 10px; border-radius: 5px;">
                ← Volver al Inicio
            </a>
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
