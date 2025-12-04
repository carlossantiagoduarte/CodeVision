<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Information | CodeVision</title>
    <link rel="stylesheet" href="../styles/event-information.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">

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
            <!-- LOGO -->
            <img src="../images/logo.png" class="logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">

            

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
        <h2 class="hero-title">Información del Evento</h2>
    </section>

    <div class="event">
        <div class="form-container">
            <h2>Datos del Evento</h2>

            <form id="eventForm">

                <!-- ================= PASO 1 ================= -->
                <div class="step active" id="step1">

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre del Evento</label>
                            <input type="text" value="{{ $event->title }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Organización</label>
                            <input type="text" value="{{ $event->organizer }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Lugar</label>
                            <input type="text" value="{{ $event->location }}" disabled>
                        </div>
                    </div>

                    <label>Descripción</label>
                    <textarea disabled>{{ $event->description }}</textarea>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Correo</label>
                            <input type="email" value="{{ $event->email }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" value="{{ $event->phone }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Capacidad</label>
                            <input type="number" value="{{ $event->max_participants }}" disabled>
                        </div>
                    </div>

                    <label>Requisitos</label>
                    <textarea disabled>{{ $event->requirements }}</textarea>

                    <div class="buttons">
                        <button type="button" id="editarBtn">Editar</button>
                        <button type="button" id="eliminarBtn">Eliminar</button>
                        <button type="button" id="nextBtn">Siguiente →</button>
                    </div>

                </div>

                <!-- ================= PASO 2 ================= -->
                <div class="step" id="step2">

                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha Inicio</label>
                            <input type="date" value="{{ $event->start_date }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Fecha Fin</label>
                            <input type="date" value="{{ $event->end_date }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Imagen URL</label>
                            <input type="url" value="{{ $event->image_url }}" disabled>
                        </div>
                    </div>

                    <label>Documentos</label>
                    <textarea disabled>{{ $event->documents_info }}</textarea>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Hora Inicio</label>
                            <input type="time" value="{{ $event->start_time }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Hora Fin</label>
                            <input type="time" value="{{ $event->end_time }}" disabled>
                        </div>
                    </div>

                    <div class="buttons">
                        <button type="button" id="prevBtn">← Anterior</button>
                        <button type="submit" id="editarBtn">Guardar</button>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <!-- ================= SCRIPT CORREGIDO ================= -->
    <script>
        const editarBtn = document.getElementById("editarBtn");
        const eliminarBtn = document.getElementById("eliminarBtn");
        const inputs = document.querySelectorAll("input, textarea");
        const nextBtn = document.getElementById("nextBtn");
        const prevBtn = document.getElementById("prevBtn");
        const step1 = document.getElementById("step1");
        const step2 = document.getElementById("step2");
        const form = document.getElementById("eventForm");

        editarBtn.addEventListener("click", () => {
            inputs.forEach(el => {
                el.disabled = false;
                el.classList.add("editable");
            });
        });

        nextBtn.addEventListener("click", () => {
            step1.classList.remove("active");
            step2.classList.add("active");
        });

        prevBtn.addEventListener("click", () => {
            step2.classList.remove("active");
            step1.classList.add("active");
        });

        eliminarBtn.addEventListener("click", () => {
            if (confirm("¿Eliminar todo el evento?")) {
                form.reset();
                step2.classList.remove("active");
                step1.classList.add("active");

                inputs.forEach(el => {
                    el.disabled = true;
                    el.classList.remove("editable");
                });
            }
        });

        form.addEventListener("submit", e => {
            e.preventDefault();
            alert("Evento actualizado ✅");

            inputs.forEach(el => {
                el.classList.remove("editable");
                el.disabled = true; // opcional: vuelve a bloquear los campos
            });
        });
    </script>

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
