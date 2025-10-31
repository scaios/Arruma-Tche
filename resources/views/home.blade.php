<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arruma, Tchê - Reporte Problemas na Sua Cidade</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
        <h1>Reporte problemas na sua cidade</h1>
        <p>Ajude a melhorar sua cidade reportando problemas de infraestrutura. Juntos podemos construir uma cidade melhor para todos.</p>
        <a href="{{ route('complaints.create') }}" class="btn-primary">+ Fazer Reclamação</a>
    </div>
</section>

    <section class="dashboard">
        <div class="cards">
            <div class="card">
                <h3>Problemas Resolvidos</h3>
                <p class="big-number">{{ $resolvedCount }}</p>
                <span>Total de registros solucionados</span>
            </div>

            <div class="card">
                <h3>Problemas em Aberto</h3>
                <p class="big-number orange">{{ $openCount }}</p>
                <span>Em análise ou aguardando</span>
            </div>
        </div>

        <div class="claims">
            <h2>Últimas Reclamações</h2>
            <div class="claim-list">
                
                @forelse ($complaints as $complaint)
                    <div class="claim">
                        <h3>{{ $complaint->category }}</h3>
                        <p><strong>{{ $complaint->title }}</strong></p>
                        <p class="bairro">📍 Bairro {{ $complaint->neighborhood }}</p>
                        <p>{{ Str::limit($complaint->description, 100) }}</p>
                        
                        <span class="status {{ strtolower(str_replace(' ', '-', $complaint->status)) }}">{{ $complaint->status }}</span>
                        
                        <p class="date">{{ $complaint->created_at->format('d/m/Y') }}</p>
                    </div>
                @empty
                    <div class="claim">
                        <p>Nenhuma reclamação encontrada.</p>
                    </div>
                @endforelse
            </div>

            <div class="pagination">
                {{-- CORREÇÃO AQUI: O caminho correto usa 'pagination::' --}}
                {{ $complaints->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-section">
            <h4>Contato</h4>
            <p>Av. Pres. Lucena, 3527 - Centro, Ivoti - RS, 93900-000</p>
            <p>(51) 3563-8800</p>
            <p>gabinete@ivoti.rs.gov.br</p>
            <p>Segunda a Sexta: 10h às 17h</p>
        </div>
        <div class="footer-section">
            <h4>Links Úteis</h4>
            <ul>
                <li><a href="#">Portal da Transparência</a></li>
                <li><a href="#">Ouvidoria</a></li>
                <li><a href="#">Serviços Online</a></li>
                <li><a href="#">Notícias</a></li>
                <li><a href="#">Concursos</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Redes Sociais</h4>
            <div class="socials">
                <a href="#">🌐</a><a href="#">📘</a><a href="#">📷</a><a href="#">🐦</a>
            </div>
        </div>
    </footer>

    <div class="footer-bottom">
        © 2025 Arruma, Tchê. Todos os direitos reservados.
    </div>
</body>
</html>