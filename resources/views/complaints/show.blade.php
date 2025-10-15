<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- O título da página será o título da reclamação --}}
    <title>Detalhes: {{ $complaint->title }} - Arruma, Tchê</title>
    {{-- Link para o novo arquivo de CSS que vamos criar --}}
    <link rel="stylesheet" href="{{ asset('css/complaint-detail.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    {{-- Incluindo o mesmo header das outras páginas para manter a consistência --}}
    <header class="header">
        <div class="brasao-area">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_ivoti.jpg') }}" alt="Brasão de Ivoti" class="brasao">
            </a>
            <h1 class="brand-name">Arruma, Tchê</h1>
        </div>
        
        <nav class="nav">
            <a href="{{ route('home') }}">Início</a>
            <a href="{{ route('complaints.create') }}">Fazer Reclamação</a>
            <a href="{{ route('complaints.my') }}">Minhas Reclamações</a>
            <a href="{{ route('about') }}">Sobre/Ajuda</a>
    
            <div class="auth-links">
                @auth
                    <a href="{{ route('dashboard') }}">Painel</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
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

    <main class="container">
        {{-- Botão para voltar para a lista --}}
        <a href="{{ route('complaints.my') }}" class="back-link">&larr; Voltar para Minhas Reclamações</a>

        <div class="complaint-detail">
            <div class="complaint-gallery">
                @if ($complaint->photo_path)
                    <img src="{{ asset('storage/' . $complaint->photo_path) }}" alt="Foto da Reclamação">
                @else
                    <img src="{{ asset('images/sem-imagem.png') }}" alt="Sem imagem disponível">
                @endif
            </div>

            <div class="complaint-info">
                <span class="status status-{{ strtolower(str_replace(' ', '-', $complaint->status)) }}">
                    {{ $complaint->status }}
                </span>
                <h1>{{ $complaint->title }}</h1>
                <div class="meta">
                    <p><strong>Codigo:</strong> #{{ $complaint->id }}</p>
                    <p><strong>Categoria:</strong> {{ $complaint->category }}</p>
                    <p><strong>Bairro:</strong> {{ $complaint->neighborhood }}</p>
                    <p><strong>Endereço:</strong> {{ $complaint->address }}</p>
                    <p><strong>Data de Abertura:</strong> {{ $complaint->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>
                <div class="description">
                    <h2>Descrição do Problema</h2>
                    {{-- nl2br() converte quebras de linha do texto em <br> no HTML --}}
                    <p>{!! nl2br(e($complaint->description)) !!}</p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>