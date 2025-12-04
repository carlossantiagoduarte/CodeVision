<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Unirse a equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('styles/join-team.css') }}"> 
    <link href="https://fonts.googleapis.com/css2?family=Kadwa:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="site-title">CodeVision</span>
        </div>
        <div class="user-menu-container">
            <div class="user-name">{{ Auth::user()->name }}</div>
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
