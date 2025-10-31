<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Reclamação Recebida</title>
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; }
        .container { width: 90%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { font-size: 24px; color: #004d73; }
        .content p { margin-bottom: 15px; }
        .content strong { color: #004d73; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header">Sua Reclamação foi Recebida!</h1>
        <div class="content">
            {{-- Usamos $complaint->user->name para pegar o nome do usuário que fez a reclamação --}}
            <p>Olá, {{ $complaint->user->name ?? 'Cidadão' }}.</p>
            <p>Recebemos sua reclamação e ela já está em nosso sistema. Obrigado por ajudar a melhorar nossa cidade!</p>
            <hr>
            <h3>Detalhes da sua Reclamação:</h3>
            <p><strong>Código:</strong> #{{ $complaint->id }}</p>
            <p><strong>Título:</strong> {{ $complaint->title }}</p>
            <p><strong>Categoria:</strong> {{ $complaint->category }}</p>
            <p><strong>Status Atual:</strong> {{ $complaint->status }}</p>
        </div>
    </div>
</body>
</html>