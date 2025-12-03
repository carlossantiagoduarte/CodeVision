<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/crear-team.css') }}"> 
    <link href="https://fonts.googleapis.com/css2?family=Kadwa:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="site-title">CodeVision</span>
        </div>
        <div class="user-menu-container">
            <div class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</div>
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
