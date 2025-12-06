<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Information | CodeVision</title>
    <link rel="stylesheet" href="../styles/equipos.css">
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
    <div class="logo-container" onclick="window.location='{{ route('dashboard') }}'" style="cursor: pointer;">
        <img src="../images/logo.png" class="logo" alt="Logo">
    </div>
    
    <!-- TÍTULO CODEVISION -->
    <div class="site-title-container" onclick="window.location='{{ route('dashboard') }}'" style="cursor: pointer;">
        <span class="site-title">CodeVision</span>
    </div>
</div>

        <div class="user-menu-container">

           <!-- NOMBRE DEL USUARIO -->
<div id="user-toggle" class="user-name">
    {{ auth()->user()->name }}  <!-- Mostrar nombre del usuario desde la base de datos -->

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

        <a href="{{ route('solicitudes') }}"> <!-- Enlace actualizado a las solicitudes -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M8 12l3 3 5-6" />
                    </svg>
                    </svg>
                    Solicitudes
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

        </div>

    </nav>

    <h1 id="tituloRol"></h1>

    <table id="tablaEquipos">
        <thead>
            <tr>
                <th>#</th>
                <th>Equipo</th>
                <th>Tu calificación</th>
                <th>Promedio</th>
                <th id="colAccion">Acción</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="footer-controls">

        <div id="paginacion"></div>

        <div id="panelExtra"></div>

    </div>

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

    <script>
        // ✅ CAMBIA EL ROL AQUÍ
        const ROL_USUARIO = "ADMIN"; // JUEZ | ESTUDIANTE | ADMIN

        // ✅ DATA
        const equipos = Array.from({
            length: 32
        }, (_, i) => ({
            id: i + 1,
            nombre: "Equipo " + (i + 1),
            calificaciones: [],
            miCalificacion: null
        }));

        const porPagina = 10;
        let paginaActual = 1;

        document.addEventListener("DOMContentLoaded", () => {
            configurarVista();
            renderPagina(1);
        });

        // ✅ CONFIGURAR SEGÚN ROL
        function configurarVista() {
            const titulo = document.getElementById("tituloRol");
            const colAccion = document.getElementById("colAccion");
            const panelExtra = document.getElementById("panelExtra");

            if (ROL_USUARIO === "JUEZ") {
                titulo.innerText = "Equipos a evaluar";
                panelExtra.innerHTML = "";
            }

            if (ROL_USUARIO === "ESTUDIANTE") {
                titulo.innerText = "Resultados del evento";
                colAccion.style.display = "none";
                panelExtra.innerHTML = `
    <div class="subir-box">
        <input type="file" id="archivo" hidden>
        <button onclick="location.href='{{ route('envio') }}'">Subir proyecto</button>
        <p id="nombreArchivo"></p>
    </div>
`;
                document.getElementById("panelExtra").style.marginLeft = "auto";
            }


            if (ROL_USUARIO === "ADMIN") {
                titulo.innerText = "Panel administrativo";
                colAccion.style.display = "none";

                panelExtra.innerHTML = `
            <div class="admin-box">
                <button class="btn-admin" onclick="location.href='{{ route('events.edit.last') }}'">
    Ver información del evento
</button>

            </div>
        `;
                panelExtra.style.marginLeft = "auto";
            }
        }



        // ✅ RENDER TABLA
        function renderPagina(pagina) {
            const tbody = document.querySelector("#tablaEquipos tbody");
            tbody.innerHTML = "";

            const inicio = (pagina - 1) * porPagina;
            const page = equipos.slice(inicio, inicio + porPagina);

            page.forEach(e => {
                const fila = document.createElement("tr");

                fila.innerHTML = `
            <td>${e.id}</td>
            <td>${e.nombre}</td>
            <td>${e.miCalificacion ?? "-"}</td>
            <td>${promedio(e.calificaciones)}</td>
            ${ROL_USUARIO === "JUEZ" ? `
    <td>
        <button onclick="irACalificar()">
            ${e.miCalificacion ? "Editar calificación" : "Calificar"}
        </button>
    </td>
` : ""}
        `;

                tbody.appendChild(fila);
            });

            renderPaginacion();
        }

        // ✅ PAGINACIÓN
        function renderPaginacion() {
            const pag = document.getElementById("paginacion");
            pag.innerHTML = "";
            let total = Math.ceil(equipos.length / porPagina);

            for (let i = 1; i <= total; i++) {
                pag.innerHTML += `<button onclick="renderPagina(${i})">${i}</button>`;
            }
        }

        // ✅ CALIFICA
        function calificar(id) {
            const equipo = equipos.find(e => e.id === id);
            let nota = prompt("Ingresa calificación (0-100)", equipo.miCalificacion ?? "");
            if (nota !== null && nota >= 0 && nota <= 100) {
                equipo.miCalificacion = Number(nota);
                equipo.calificaciones.push(equipo.miCalificacion);
                renderPagina(paginaActual);
            }
        }

        // ✅ PROMEDIO
        function promedio(a) {
            if (a.length === 0) return "-";
            return (a.reduce((s, v) => s + v, 0) / a.length).toFixed(1);
        }

        // ✅ SUBIR ARCHIVO
        document.addEventListener("change", e => {
            if (e.target.id === "archivo") {
                document.getElementById("nombreArchivo").innerText = e.target.files[0]?.name || "";
            }
        });

        function irACalificar() {
        // Redirigir a la ruta de calificación
        window.location.href = '/calificar-equipo';
        }
    </script>

</body>

</html>
