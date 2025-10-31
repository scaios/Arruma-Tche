<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Reclamação Atualizada</title>
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; }
        .container { width: 90%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { font-size: 24px; color: #004d73; }
        .content p { margin-bottom: 15px; }
        .content strong { color: #004d73; }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
        }
        /* Define as cores com base no status */
        .status-aberto { background-color: #e74c3c; }
        .status-em-análise { background-color: #f1c40f; color: #333; }
        .status-resolvido { background-color: #219752; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header">Sua Reclamação foi Atualizada!</h1>
        <div class="content">
            {{-- Usamos $complaint->user->name para pegar o nome do usuário --}}
            <p>Olá, {{ $complaint->user->name ?? 'Cidadão' }}.</p>
            <p>A sua reclamação (Código: #{{ $complaint->id }}) teve o status alterado pela administração.</p>
            <hr>
            <h3>Detalhes da Atualização:</h3>
            <p><strong>Título:</strong> {{ $complaint->title }}</p>
            <p>
                <strong>Novo Status:</strong> 
                <span class
="status-badge status-{{ strtolower(str_replace(' ', '-', $complaint->status)) }}">{{ $complaint->status }}</span>
            </p>
        </div>
    </div>
</body>
</html>