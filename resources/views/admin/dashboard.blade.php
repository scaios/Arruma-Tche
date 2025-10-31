<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - Arruma, Tchê</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <h1>Painel de Administração</h1>
        <nav>
            <a href="{{ route('home') }}">Voltar ao Site</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Sair</a>
            </form>
        </nav>
    </header>

    <main class="container">
        <h2>Todas as Reclamações</h2>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Categoria</th>
                    <th>Bairro</th>
                    <th>Usuário</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($complaints as $complaint)
                    <tr class="clickable-row" data-id="{{ $complaint->id }}">
                        <td>#{{ $complaint->id }}</td>
                        <td>{{ Str::limit($complaint->title, 40) }}</td>
                        <td>{{ $complaint->category }}</td>
                        <td>{{ $complaint->neighborhood }}</td>
                        <td>{{ $complaint->user?->name ?? 'N/A' }}</td>
                        <td>{{ $complaint->created_at->format('d/m/Y') }}</td>
                        <td><span class="status status-{{ strtolower(str_replace(' ', '-', $complaint->status)) }}">{{ $complaint->status }}</span></td>
                        <td>
                            <form action="{{ route('admin.complaints.updateStatus', $complaint->id) }}" method="POST" class="action-form" onclick="event.stopPropagation();">
                                @csrf
                                @method('PATCH')
                                <select name="status">
                                    <option value="Aberto" {{ $complaint->status == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                                    <option value="Em Análise" {{ $complaint->status == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                                    <option value="Resolvido" {{ $complaint->status == 'Resolvido' ? 'selected' : '' }}>Resolvido</option>
                                </select>

                                <textarea 
                                    name="admin_comment" 
                                    rows="3" 
                                    placeholder="Adicionar comentário público..." 
                                    style="width: 100%; margin-top: 5px; border-radius: 6px; border: 1px solid #ccc; padding: 5px; font-family: 'Inter', sans-serif;"
                                >{{ $complaint->admin_comment }}</textarea>
                                <button type="submit" class="btn-save">Salvar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">Nenhuma reclamação encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>

    <div id="complaintModal" class="modal-overlay hidden">
        <div class="modal-content">
            <button id="closeModalBtn" class="modal-close">&times;</button>
            <div id="modal-body">
                <h2 id="modal-title"></h2>
                <img id="modal-photo" src="" alt="Foto da Reclamação" class="hidden">
                <div class="modal-meta">
                    <p><strong>Codigo:</strong> <span id="modal-protocol"></span></p>
                    <p><strong>Usuário:</strong> <span id="modal-user-email"></span></p>
                    <p><strong>Categoria:</strong> <span id="modal-category"></span></p>
                    <p><strong>Bairro:</strong> <span id="modal-neighborhood"></span></p>
                    <p><strong>Endereço:</strong> <span id="modal-address"></span></p>
                </div>
                <h3>Descrição</h3>
                <p id="modal-description"></p>

                <div id="modal-admin-comment-section" class="hidden" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                    <h3>Comentário do Administrador</h3>
                    <p id="modal-admin-comment" style="white-space: pre-wrap; background: #f8f9fa; padding: 10px; border-radius: 5px;"></p>
                </div>
                </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('complaintModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const tableRows = document.querySelectorAll('.clickable-row');

    const closeModal = () => modal.classList.add('hidden');

    tableRows.forEach(row => {
        row.addEventListener('click', function () {
            const complaintId = this.dataset.id;
            const url = `/admin/complaints/${complaintId}/details`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-title').textContent = data.title;
                    document.getElementById('modal-protocol').textContent = `#${data.id}`;
                    document.getElementById('modal-user-email').textContent = data.user ? data.user.email : 'N/A';
                    document.getElementById('modal-category').textContent = data.category;
                    document.getElementById('modal-neighborhood').textContent = data.neighborhood;
                    document.getElementById('modal-address').textContent = data.address;
                    document.getElementById('modal-description').textContent = data.description;
                    
                    const photoElement = document.getElementById('modal-photo');
                    if (data.photo_path) {
                        photoElement.src = `/storage/${data.photo_path}`;
                        photoElement.classList.remove('hidden');
                    } else {
                        photoElement.classList.add('hidden');
                    }

                    // --- LÓGICA DO COMENTÁRIO DO ADMIN ADICIONADA ---
                    const commentSection = document.getElementById('modal-admin-comment-section');
                    const commentText = document.getElementById('modal-admin-comment');

                    if (data.admin_comment) {
                        commentText.textContent = data.admin_comment;
                        commentSection.classList.remove('hidden');
                    } else {
                        commentSection.classList.add('hidden');
                    }
                    // --- FIM DA LÓGICA DO COMENTÁRIO ---

                    modal.classList.remove('hidden');
                })
                .catch(error => console.error('Erro ao buscar detalhes:', error));
        });
    });

    closeModalBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
    });
});
</script>

</body>
</html>