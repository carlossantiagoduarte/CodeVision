<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('styles/crear-team.css') }}"> 
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

    <form action="{{ route('teams.store') }}" method="POST" id="mainForm">
        @csrf <input type="hidden" name="event_id" value="{{ $event_id ?? 1 }}">

        <section id="step1" class="step {{ session('success_code') ? '' : 'active' }}">
            <div class="header-banner">Registra tu Equipo Aquí!</div>
            <div class="form-container">
                <h1 class="title">Formulario de Inscripción</h1>

                @if ($errors->any())
                    <div style="background: #ffdede; color: red; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-grid">
                    <div class="event-card">
                        <h3>Evento ID: {{ $event_id ?? 1 }}</h3>
                        <p>Completa los datos para inscribirte.</p>
                    </div>

                    <div class="inputs-container">
                        <label>Nombre del Equipo</label>
                        <input type="text" name="name" required placeholder="Nombre de tu equipo" value="{{ old('name') }}">

                        <label>Nombre del Líder</label>
                        <input type="text" name="leader_name" required placeholder="Tu nombre completo" value="{{ Auth::user()->name ?? '' }}">

                        <label>Correo Electrónico</label>
                        <input type="email" name="leader_email" required placeholder="example@gmail.com" value="{{ Auth::user()->email ?? '' }}">

                        <label>Carrera</label>
                        <input type="text" name="leader_career" required placeholder="Sistemas, Gestión, etc." value="{{ old('leader_career') }}">

                        <label>Semestre</label>
                        <input type="text" name="leader_semester" required placeholder="Ej: 8vo" value="{{ old('leader_semester') }}">

                        <label>Experiencia (Opcional)</label>
                        <textarea name="leader_experience" placeholder="Experiencia con la que cuentas">{{ old('leader_experience') }}</textarea>

                        <label>Número de integrantes (Max)</label>
                        <input type="number" name="max_members" id="totalMembers" min="2" value="5" required>

                        <label>Visibilidad</label>
                        <select name="visibility">
                            <option value="Privado">Privado</option>
                            <option value="Público">Público</option>
                        </select>

                        <button type="button" class="btn-primary" onclick="goToStep2()">Siguiente</button>
                    </div>
                </div>
            </div>
        </section>

        <section id="step2" class="step">
            <div class="header-banner">Detalles Finales</div>
            <div class="members-container">
                <h2>Miembros y Requisitos</h2>

                <div id="membersDynamic"></div>

                <label>Requisitos de los participantes</label>
                <textarea name="requirements" placeholder="Escribe los requisitos necesarios..."></textarea>

                <div class="buttons-row">
                    <button type="button" class="btn-secondary" onclick="goToStep1()">Atrás</button>
                    <button type="submit" class="btn-primary">Finalizar y Crear Equipo</button>
                </div>
            </div>
        </section>
    </form>

    <section id="step3" class="step {{ session('success_code') ? 'active' : '' }}">
        <div class="final-container">
            <h1>¡Equipo creado con éxito!</h1>
            <p>Este es tu código de invitación generado por el sistema:</p>

            <div class="invite-box">
                <h3>CÓDIGO DE INVITACIÓN</h3>
                <div id="inviteCode">{{ session('success_code') }}</div>

                <button type="button" class="btn-copy" onclick="copyCode()">
                    Copiar código
                </button>
            </div>

            <a href="{{ route('dashboard') }}" class="btn-primary">Ir al Dashboard</a>
        </div>
    </section>

    <script>
        function showStep(num) {
            document.querySelectorAll(".step").forEach(s => s.classList.remove("active"));
            document.querySelector("#step" + num).classList.add("active");
        }

        function goToStep1() {
            showStep(1);
        }

        function goToStep2() {
            const total = parseInt(document.getElementById("totalMembers").value);
            if (total < 2) return alert("Debe haber al menos 2 integrantes.");
            
            // Generamos inputs visuales (nota: estos datos no se guardan en la tabla 'teams' actual, 
            // pero se enviarán al controlador si quieres guardarlos en otra tabla 'team_members')
            generateMembers(total - 1);
            showStep(2);
        }

        function generateMembers(count) {
            const container = document.getElementById("membersDynamic");
            container.innerHTML = "";
            for (let i = 1; i <= count; i++) {
                container.innerHTML += `
                <div class="member-box">
                    <h3>Miembro ${i} (Pendiente de invitación)</h3>
                    <p style="font-size: 0.9em; color: gray;">Los datos de los miembros se gestionarán con el código de invitación.</p>
                </div>`;
            }
        }

        function copyCode() {
            const code = document.getElementById("inviteCode").innerText;
            navigator.clipboard.writeText(code);
            alert("Código copiado: " + code);
        }
    </script>
</body>
</html>
