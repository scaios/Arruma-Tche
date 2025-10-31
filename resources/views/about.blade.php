<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sobre / Ajuda - Arruma, Tchê</title>
  {{-- Caminho correto para o CSS na pasta public/css --}}
  <link rel="stylesheet" href="{{ asset('css/sobre.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <header class="header">
    <div class="brasao-area">
      {{-- Caminho correto para a imagem na pasta public/images --}}
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
      <h1>Sobre o Sistema de Reclamações</h1>
      <p>Saiba como utilizar nossa plataforma para melhorar nossa cidade</p>
    </div>
  </section>

  <main class="sobre-main">
    <section class="como-usar">
      <h2>Como Usar o Sistema</h2>
      <div class="steps">
        <div class="step">
          {{-- Caminho correto para os ícones na pasta public/icones --}}
          <img src="{{ asset('images/login.png') }}" alt="Ícone Login">
          <h3>Passo 1</h3>
          <p>Crie sua conta ou faça login com seus dados cadastrais.</p>
        </div>
        <div class="step">
          <img src="{{ asset('images/reclamacao.png') }}" alt="Ícone Reclamação">
          <h3>Passo 2</h3>
          <p>Acesse a opção "Fazer Reclamação" no menu principal.</p>
        </div>
        <div class="step">
          <img src="{{ asset('images/formulario.png') }}" alt="Ícone Formulário">
          <h3>Passo 3</h3>
          <p>Preencha o formulário com todos os detalhes da reclamação.</p>
        </div>
        <div class="step">
          <img src="{{ asset('images/enviar.png') }}" alt="Ícone Enviar">
          <h3>Passo 4</h3>
          <p>Envie sua reclamação e guarde o número de Codigo.</p>
        </div>
        <div class="step">
          <img src="{{ asset('images/acompanhar.png') }}" alt="Ícone Acompanhar">
          <h3>Passo 5</h3>
          <p>Acompanhe o status da sua reclamação usando o Codigo.</p>
        </div>
      </div>
    </section>

    <section class="dicas">
      <h2>Dicas para Reclamações Efetivas</h2>
      <div class="dicas-grid">
        <div class="dica">
          <h3>Localização Precisa</h3>
          <p>Forneça o endereço completo e referências do local. Inclua coordenadas GPS, se possível.</p>
        </div>
        <div class="dica">
          <h3>Anexe Fotos</h3>
          <p>Imagens do problema ajudam na avaliação. Tire fotos claras e de diferentes ângulos.</p>
        </div>
        <div class="dica">
          <h3>Descrição Detalhada</h3>
          <p>Informe o problema com clareza: quando começou, gravidade e impacto na comunidade.</p>
        </div>
      </div>
    </section>

    <section class="prazos">
      <h2>Prazos de Atendimento</h2>
      <ul>
        <li><strong>Recebimento e Confirmação:</strong> Sua reclamação é registrada e você recebe um número de Codigo em até 24h.</li>
        <li><strong>Análise Inicial:</strong> Em até 3 dias úteis, nossa equipe analisa e encaminha ao setor responsável.</li>
        <li><strong>Avaliação Técnica:</strong> O setor avalia a situação e define um plano de ação em até 7 dias úteis.</li>
        <li><strong>Resolução:</strong> O prazo varia conforme o problema, podendo levar de 15 a 45 dias.</li>
      </ul>
    </section>
  </main>

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
        <a href="#">🌐</a>
        <a href="#">📘</a>
        <a href="#">📷</a>
        <a href="#">🐦</a>
      </div>
    </div>
  </footer>

  <div class="footer-bottom">
    © 2025 Arruma, Tchê. Todos os direitos reservados.
  </div>
</body>
</html>