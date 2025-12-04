<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Information | CodeVision</title>
    <!-- NOTA: Laravel no recomienda enlaces relativos para assets. Usa asset() o Vite. -->
    <link rel="stylesheet" href="{{ asset('styles/equipos.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
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
        /* Estilos CSS mínimos para los componentes dinámicos */
        .admin-box, .subir-box {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: auto;
        }
        .btn-admin {
            padding: 10px 20px;
            background-color: #3b82f6; /* Blue 500 */
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .subir-box button {
            padding: 10px 20px;
            background-color: #10b981; /* Green 500 */
            color: white;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
        #tablaEquipos {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        #tablaEquipos th, #tablaEquipos td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        #tituloRol {
            text-align: center;
            margin-top: 30px;
            font-family: 'Kadwa', serif;
        }
        .footer-controls {
            display: flex;
            justify-content: space-between;
            width: 90%;
            margin: 20px auto;
        }
    </style>
</head>

<body>

    <!-- NAVBAR (Se asume que Auth::user() está disponible) -->
    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ auth()->user()->name }} 

                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard') }}">
                    <!-- Iconos SVG omitidos para brevedad -->
                    Inicio
                </a>
                <a href="{{ route('profile.edit') }}"> 
                    <!-- Iconos SVG omitidos para brevedad -->
                    Perfil
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <a href="#" class="btn-search" onclick="this.closest('form').submit();">
                        <!-- Iconos SVG omitidos para brevedad -->
                        Cerrar sesión
                    </a>
                </form>
            </div>
        </div>
    </nav>

    {{-- LÓGICA DE TÍTULO BASADA EN ROLES --}}
    <h1 id="tituloRol">
        @role('Admin') Panel administrativo @endrole
        @role('Juez') Equipos a evaluar @endrole
        @role('Estudiante') Resultados del evento @endrole
        {{-- Fallback si solo está autenticado --}}
        @if(auth()->check() && !auth()->user()->hasAnyRole(['Admin', 'Juez', 'Estudiante'])) Equipos y Eventos @endif
    </h1>

    <table id="tablaEquipos">
        <thead>
            <tr>
                <th>#</th>
                <th>Equipo</th>
                <th>Tu calificación</th>
                <th>Promedio</th>
                {{-- La columna de Acción solo se muestra si el usuario es Juez --}}
                @role('Juez')
                    <th id="colAccion">Acción</th>
                @endrole
            </tr>
        </thead>
        <tbody>
            {{-- Aquí iría un loop @foreach($equipos as $equipo) si tuvieras datos de Laravel --}}
        </tbody>
    </table>

    <div class="footer-controls">
        <div id="paginacion"></div>

        <div id="panelExtra">
            {{-- LÓGICA DE PANEL EXTRA BASADA EN ROLES --}}
            
            @role('Estudiante')
                <div class="subir-box">
                    <input type="file" id="archivo" hidden>
                    {{-- Usamos @can para la subida de proyecto --}}
                    @can('enviar proyecto')
                        <button onclick="location.href='{{ route('envio') }}'">Subir proyecto</button>
                    @endcan
                    <p id="nombreArchivo"></p>
                </div>
            @endrole

            @role('Admin')
                <div class="admin-box">
                    {{-- El admin puede ver información del evento, que requiere el permiso 'ver eventos' --}}
                    @can('ver eventos')
                        <a href="{{ route('events.edit.last') }}" class="btn-admin">
                            Ver información del evento
                        </a>
                    @endcan
                </div>
            @endrole

        </div>
    </div>

    <!-- FOOTER (Omitido por brevedad, se mantiene el contenido) -->
    <footer class="footer">
        <!-- ... Contenido del footer ... -->
    </footer>

    <script>
        // *******************************************************
        // LÓGICA JS ADAPTADA
        // La variable ROL_USUARIO SE ELIMINA ya que usamos Blade.
        // *******************************************************

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
            // El JS ya no necesita llamar a configurarVista(), Blade lo hace.
            renderPagina(1);
        });

        // ✅ RENDER TABLA
        function renderPagina(pagina) {
            const tbody = document.querySelector("#tablaEquipos tbody");
            tbody.innerHTML = "";

            const inicio = (pagina - 1) * porPagina;
            const page = equipos.slice(inicio, inicio + porPagina);

            // Determinar si el usuario es juez (usando JS para la lógica dinámica de la fila)
            // En una aplicación Laravel real, esta variable se pasaría desde el Controller.
            const esJuez = !!document.getElementById('colAccion'); 

            page.forEach(e => {
                const fila = document.createElement("tr");

                let accionBoton = '';

                // Solo si la columna 'Acción' está visible (lo cual Blade ya hizo para JUEZ)
                if (esJuez) {
                    accionBoton = `
                        <td>
                            {{-- Aquí deberías usar la ruta real para calificar el equipo con su ID --}}
                            <button onclick="window.location.href = '/calificar-equipo/${e.id}'">
                                ${e.miCalificacion ? "Editar calificación" : "Calificar"}
                            </button>
                        </td>
                    `;
                }

                fila.innerHTML = `
                    <td>${e.id}</td>
                    <td>${e.nombre}</td>
                    <td>${e.miCalificacion ?? "-"}</td>
                    <td>${promedio(e.calificaciones)}</td>
                    ${accionBoton}
                `;

                tbody.appendChild(fila);
            });

            renderPaginacion();
        }

        // ✅ PAGINACIÓN
        function renderPaginacion() {
            // ... (Lógica de paginación JS, no se necesita cambiar) ...
        }

        // ✅ PROMEDIO
        function promedio(a) {
            if (a.length === 0) return "-";
            return (a.reduce((s, v) => s + v, 0) / a.length).toFixed(1);
        }

        // ✅ SUBIR ARCHIVO (Lógica de JS, no se necesita cambiar)
        document.addEventListener("change", e => {
            if (e.target.id === "archivo") {
                document.getElementById("nombreArchivo").innerText = e.target.files[0]?.name || "";
            }
        });

        // La función irACalificar ya no se usa, pero la dejamos como referencia
        function irACalificar() {
            window.location.href = '{{ route("teams.calificar") }}'; 
        }

    </script>

</body>

</html>
