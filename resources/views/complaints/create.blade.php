<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer Reclamação - Arruma, Tchê</title>
    <link rel="stylesheet" href="{{ asset('css/fazer_reclamacao.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
</head>
<body>
    <header class="header">
        <div class="brasao-area">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_ivoti.jpg') }}" alt="Brasão de Ivoti" class="brasao">
            </a>
            <h1 class="brand-name">Arruma, Tchê</h1>
        </div>
        <nav class="nav">
    <div class="main-nav">
        <a href="{{ route('home') }}" class="{{ Request::routeIs('home') ? 'active' : '' }}">Início</a>
        <a href="{{ route('complaints.create') }}" class="{{ Request::routeIs('complaints.create') ? 'active' : '' }}">Fazer Reclamação</a>
        <a href="{{ route('complaints.my') }}" class="{{ Request::routeIs('complaints.my') ? 'active' : '' }}">Minhas Reclamações</a>
        <a href="{{ route('about') }}" class="{{ Request::routeIs('about') ? 'active' : '' }}">Sobre/Ajuda</a>
    </div>
    <div class="auth-links">
        @auth
            <a href="{{ route('dashboard') }}">Painel</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    Sair
                </a>
            </form>
        @else
            <a href="{{ route('login') }}">Entrar</a>
            <a href="{{ route('register') }}">Registrar</a>
        @endauth
    </div>
</nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Fazer Reclamação</h1>
            <p>Preencha o formulário abaixo para reportar um problema na infraestrutura da cidade.</p>
        </div>
    </section>

    <main class="form-container">
        <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="complaint-form">
            @csrf
            <h2>Detalhes da Reclamação</h2>

            <div class="form-group">
                <label for="category">Categoria do problema</label>
                <p>Selecione a categoria que melhor descreve o problema:</p>
                <select name="category" id="category" required>
                    <option value="" disabled selected>Escolha uma categoria...</option>
                    <option value="Buraco na via">Buraco na via</option>
                    <option value="Iluminação">Iluminação</option>
                    <option value="Vazamento">Vazamento</option>
                    <option value="Lixo e Entulho">Lixo e Entulho</option>
                    <option value="Áreas Verdes">Áreas Verdes (Praças, Parques)</option>
                    <option value="Trânsito">Trânsito (Sinalização, Semáforos)</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Título da reclamação</label>
                <input type="text" id="title" name="title" placeholder="Ex: Buraco na Avenida Principal" required>
            </div>

            <div class="form-group">
                <label for="description">Descrição detalhada</label>
                <textarea id="description" name="description" rows="5" placeholder="Descreva o problema com o máximo de detalhes possível..." required></textarea>
            </div>

            <div class="form-group">
                <label for="photo"><strong>Upload de foto (opcional)</strong></label>
                <div class="upload-box">
                    <input type="file" id="photo" name="photo" accept="image/png, image/jpeg">
                    <small>Formatos aceitos: JPG, PNG (máx. 10MB)</small>
                </div>
            </div>
            
            <h3>Localização</h3>
            
            <div class="form-group">
                <label for="neighborhood">Bairro</label>
                <input type="text" id="neighborhood" name="neighborhood" placeholder="Digite o bairro" required>
            </div>
            
            <div class="form-group">
                <label for="address">Endereço ou localização</label>
                <input type="text" id="address" name="address" placeholder="Digite a rua e o número aproximado" required>
            </div>

            <div class="form-group">
                <button type="button" id="toggleMapBtn" class="btn-secondary">Adicionar Localização Precisa no Mapa</button>
            </div>

            <div id="map-container" style="display: none;">
                <div class="form-group">
                    <label for="map">Clique no mapa para adicionar um marcador</label>
                    <div id="map"></div> 
                </div>
            </div>

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            {{-- --- INÍCIO DA ALTERAÇÃO --- --}}
            {{-- REMOVIDO: <h3>Informações de contato</h3> --}}
            {{-- REMOVIDO: <div class="contact-info">...</div> --}}
            {{-- --- FIM DA ALTERAÇÃO --- --}}

            <button type="submit" class="btn-primary">Enviar Reclamação</button>
        </form>
    </main>

    <footer class="footer"><!-- O seu footer continua aqui --></footer>
    <div class="footer-bottom"><!-- O seu footer bottom continua aqui --></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // LÓGICA DO BOTÃO DE MOSTRAR/ESCONDER
        const toggleMapBtn = document.getElementById('toggleMapBtn');
        const mapContainer = document.getElementById('map-container');
        let mapInitialized = false; // Variável de controle
        let map; // Declara a variável do mapa no escopo mais alto

        toggleMapBtn.addEventListener('click', () => {
            if (mapContainer.style.display === 'none') {
                mapContainer.style.display = 'block';
                toggleMapBtn.textContent = 'Esconder Mapa';

                if (!mapInitialized) {
                    const initialCoords = [-29.5915, -51.1404];
                    map = L.map('map').setView(initialCoords, 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);
                    
                    let marker = null;
                    map.on('click', function(e) {
                        const lat = e.latlng.lat;
                        const lng = e.latlng.lng;
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        if (marker === null) {
                            marker = L.marker([lat, lng]).addTo(map);
                        } else {
                            marker.setLatLng([lat, lng]);
                        }
                    });

                    mapInitialized = true;
                }
                
                setTimeout(() => map.invalidateSize(), 10);
                
            } else {
                mapContainer.style.display = 'none';
                toggleMapBtn.textContent = 'Adicionar Localização Precisa no Mapa';
            }
        });
    </script>
    </body>
</html>