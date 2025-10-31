<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Minhas Reclamações - Arruma, Tchê</title>
    <link rel="stylesheet" href="{{ asset('css/my-complaints.css') }}">
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
            <a href="{{ route('home') }}">Início</a>
            <a href="{{ route('complaints.create') }}">Fazer Reclamação</a>
            <a href="{{ route('complaints.my') }}" class="active">Minhas Reclamações</a>
            <a href="{{ route('about') }}">Sobre/Ajuda</a>
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

    <main class="container">
        <h1>Minhas Reclamações</h1>
        <p class="subtitle">Acompanhe suas solicitações</p>

        <div class="stats">
            <div class="stat-card">
                <p>Total</p>
                <h2 class="blue">{{ $totalCount }}</h2>
            </div>
            <div class="stat-card">
                <p>Resolvidas</p>
                <h2 class="green">{{ $resolvedCount }}</h2>
            </div>
            <div class="stat-card">
                <p>Em Aberto</p>
                <h2 class="orange">{{ $openCount }}</h2>
            </div>
        </div>

        <form action="{{ route('complaints.my') }}" method="GET" class="filters">
            <select name="category">
                <option value="">Todas as categorias</option>
                <option value="Buraco na via" {{ request('category') == 'Buraco na via' ? 'selected' : '' }}>Buraco na via</option>
                <option value="Iluminação" {{ request('category') == 'Iluminação' ? 'selected' : '' }}>Iluminação</option>
                <option value="Vazamento" {{ request('category') == 'Vazamento' ? 'selected' : '' }}>Vazamento</option>
                <option value="Outros" {{ request('category') == 'Outros' ? 'selected' : '' }}>Outros</option>
            </select>
            <select name="status">
                <option value="">Todos os status</option>
                <option value="Aberto" {{ request('status') == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                <option value="Em Análise" {{ request('status') == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                <option value="Resolvido" {{ request('status') == 'Resolvido' ? 'selected' : '' }}>Resolvido</option>
            </select>
            <input type="date" name="date" value="{{ request('date') }}">
            <button type="submit" class="btn-primary">Buscar</button>
        </form>

        <div class="complaints">
            @forelse ($complaints as $complaint)
                <div class="complaint clickable" data-id="{{ $complaint->id }}">
                    <div>
                        <h3>{{ $complaint->title }}</h3>
                <p class="meta">
                    Codigo #{{ $complaint->id }} — ...
                </p>
                    </div>
                    <div class="actions">
                        <span class="status status-{{ strtolower(str_replace(' ', '-', $complaint->status)) }}">
                            {{ $complaint->status }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="complaint">
                    <p>Você ainda não fez nenhuma reclamação. Que tal começar agora?</p>
                    <a href="{{ route('complaints.create') }}" class="btn-primary">Fazer minha primeira reclamação</a>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $complaints->links() }}
        </div>
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
                <a href="#">🌐</a> <a href="#">📘</a> <a href="#">📷</a> <a href="#">🐦</a>
            </div>
        </div>
    </footer>
    <div class="footer-bottom">
        © 2025 Arruma, Tchê. Todos os direitos reservados.
    </div>

<div id="userComplaintModal" class="modal-overlay hidden">
    <div class="modal-content">
        <button id="closeUserModalBtn" class="modal-close">&times;</button>
        
        {{-- ÁREA DE VISUALIZAÇÃO (padrão) --}}
        <div id="view-mode">
            <h2 id="modal-title"></h2>
            <img id="modal-photo" src="" alt="Foto da Reclamação" class="hidden">
            <div class="modal-meta">
                <p><strong>Codigo:</strong> <span id="modal-protocol"></span></p>
                <p><strong>Categoria:</strong> <span id="modal-category"></span></p>
                <p><strong>Bairro:</strong> <span id="modal-neighborhood"></span></p>
                <p><strong>Endereço:</strong> <span id="modal-address"></span></p>
                <p><strong>Status:</strong> <span id="modal-status" class="status"></span></p>
            </div>
            <h3>Descrição</h3>
            <p id="modal-description"></p>
            
            {{-- Botões de Ação --}}
            <div class="modal-actions">
                <button id="editBtn" class="btn-edit">Editar</button>
                <button id="deleteBtn" class="btn-delete">Excluir</button>
            </div>
        </div>

        {{-- ÁREA DE EDIÇÃO (começa escondida) --}}
        <div id="edit-mode" class="hidden">
            <form id="edit-form" method="POST" action="">
                @csrf
                @method('PATCH')

                <h2>Editando Reclamação</h2>

                <div class="form-group">
                    <label for="edit-title">Título da reclamação</label>
                    <input type="text" id="edit-title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="edit-category">Categoria</label>
                    <select id="edit-category" name="category" required>
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
                    <label for="edit-description">Descrição detalhada</label>
                    <textarea id="edit-description" name="description" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit-neighborhood">Bairro</label>
                    <input type="text" id="edit-neighborhood" name="neighborhood" required>
                </div>
                
                <div class="form-group">
                    <label for="edit-address">Endereço</label>
                    <input type="text" id="edit-address" name="address" required>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="btn-save">Salvar Alterações</button>
                    <button type="button" id="cancelEditBtn" class="btn-secondary">Cancelar</button>
                </div>
            </form>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('userComplaintModal');
    const closeModalBtn = document.getElementById('closeUserModalBtn');
    const complaintCards = document.querySelectorAll('.complaint.clickable');
    
    const viewMode = document.getElementById('view-mode');
    const editMode = document.getElementById('edit-mode');
    
    const editBtn = document.getElementById('editBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const editForm = document.getElementById('edit-form');
    const deleteBtn = document.getElementById('deleteBtn');

    let currentComplaintId = null; 

    const closeModal = () => {
        modal.classList.add('hidden');
        viewMode.classList.remove('hidden');
        editMode.classList.add('hidden');
    };

    complaintCards.forEach(card => {
        card.addEventListener('click', function () {
            currentComplaintId = this.dataset.id;
            const url = `/reclamacoes/${currentComplaintId}/detalhes`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-title').textContent = data.title;
                    document.getElementById('modal-protocol').textContent = `#${data.id}`;
                    document.getElementById('modal-category').textContent = data.category;
                    document.getElementById('modal-neighborhood').textContent = data.neighborhood;
                    document.getElementById('modal-address').textContent = data.address;
                    document.getElementById('modal-description').innerHTML = data.description.replace(/\n/g, '<br>');

                    const statusSpan = document.getElementById('modal-status');
                    statusSpan.textContent = data.status;
                    statusSpan.className = 'status';
                    statusSpan.classList.add(`status-${data.status.toLowerCase().replace(' ', '-')}`);

                    const photoElement = document.getElementById('modal-photo');
                    if (data.photo_path) {
                        photoElement.src = `/storage/${data.photo_path}`;
                        photoElement.classList.remove('hidden');
                    } else {
                        photoElement.classList.add('hidden');
                    }

                    if (data.status === 'Aberto') {
                        editBtn.style.display = 'inline-block';
                        deleteBtn.style.display = 'inline-block';
                    } else {
                        editBtn.style.display = 'none';
                        deleteBtn.style.display = 'none';
                    }

                    document.getElementById('edit-title').value = data.title;
                    document.getElementById('edit-category').value = data.category;
                    document.getElementById('edit-description').value = data.description;
                    document.getElementById('edit-neighborhood').value = data.neighborhood;
                    document.getElementById('edit-address').value = data.address;

                    modal.classList.remove('hidden');
                });
        });
    });

    editBtn.addEventListener('click', () => {
        editForm.action = `/reclamacoes/${currentComplaintId}`;
        viewMode.classList.add('hidden');
        editMode.classList.remove('hidden');
    });

    cancelEditBtn.addEventListener('click', () => {
        editMode.classList.add('hidden');
        viewMode.classList.remove('hidden');
    });

    deleteBtn.addEventListener('click', () => {
        if (confirm('Tem certeza de que deseja excluir esta reclamação? Esta ação não pode ser desfeita.')) {
            const url = `/reclamacoes/${currentComplaintId}`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cardToRemove = document.querySelector(`.complaint[data-id="${currentComplaintId}"]`);
                    if (cardToRemove) {
                        cardToRemove.remove();
                    }
                    closeModal(); 
                    alert(data.success);
                } else {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Erro ao excluir:', error);
                alert('Ocorreu um erro ao tentar excluir a reclamação.');
            });
        }
    });

    closeModalBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });
});
</script>
</body>
</html>