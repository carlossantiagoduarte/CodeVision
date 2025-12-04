<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Unirse a equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('styles/join-team.css') }}"> 
    <link href="https://fonts.googleapis.com/css2?family=Kadwa:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
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

    <section class="join-layout">
        <div class="banner">
            <h1>ÚNETE A UN EQUIPO</h1>
        </div>

        <div class="content">
            <div class="card">

                <h2 id="title">Selecciona un equipo público o ingresa código</h2>
                <p id="subtitle">Explora o introduce tu invitación.</p>

                @if ($errors->any())
                    <div style="background: #ffe6e6; color: red; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="tabs">
                    <button id="btnPublic" class="active">Equipo público</button>
                    <button id="btnPrivate">Equipo privado</button>
                    <button id="btnCreate" onclick="window.location.href='{{ route('teams.create') }}'">Crear equipo</button>
                </div>

                <div id="publicSection">
                    <h3>Equipos disponibles</h3>
                    <div class="teams">
                        @if($publicTeams->count() > 0)
                            @foreach($publicTeams as $team)
                                <div class="team-card">
                                    <h4>{{ $team->name }}</h4>
                                    <span class="tag blue">{{ $team->leader_career }}</span>
                                    <p>Líder: {{ $team->leader_name }}</p>
                                    <button onclick="alert('Funcionalidad de solicitud pública pendiente')">Solicitar unirse</button>
                                </div>
                            @endforeach
                        @else
                            <p style="color: gray; text-align: center;">No hay equipos públicos disponibles.</p>
                        @endif
                    </div>
                </div>

                <div id="privateSection" class="hidden">
                    <h2>Equipo privado</h2>
                    <p>Ingresa el código que te compartió tu líder.</p>

                    <form action="{{ route('teams.processJoin') }}" method="POST">
                        @csrf
                        <div class="form">
                            <label>Código de Invitación</label>
                            <input type="text" name="invite_code" placeholder="Ej: ITO-AX45-TEAM" required>

                            <label>Tu Carrera</label>
                            <input type="text" name="career" placeholder="Sistemas, Gestión..." required>

                            <label>Teléfono de Contacto</label>
                            <input type="text" name="phone" placeholder="951..." required>
                        </div>

                        <div class="buttons">
                            <button type="submit" class="dark">Unirme al equipo</button>
                        </div>
                    </form>
                    
                    <button class="outline" id="volverPublico" style="margin-top: 10px; width: 100%;">Volver a inicio</button>
                </div>

            </div>
        </div>
    </section>

    <script>
        const btnPublic = document.getElementById("btnPublic");
        const btnPrivate = document.getElementById("btnPrivate");
        const publicSection = document.getElementById("publicSection");
        const privateSection = document.getElementById("privateSection");
        const volverPublico = document.getElementById("volverPublico");

        btnPublic.onclick = () => {
            publicSection.style.display = "block";
            privateSection.style.display = "none";
            btnPublic.classList.add("active");
            btnPrivate.classList.remove("active");
        };

        btnPrivate.onclick = () => {
            publicSection.style.display = "none";
            privateSection.style.display = "block";
            btnPrivate.classList.add("active");
            btnPublic.classList.remove("active");
        };

        volverPublico.onclick = () => btnPublic.click();
    </script>
</body>
</html>
