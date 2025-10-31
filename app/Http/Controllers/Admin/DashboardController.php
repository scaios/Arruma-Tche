<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint; // Importa o nosso Model de Reclamação
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintStatusUpdated;

class DashboardController extends Controller
{
    /**
     * Exibe o painel principal de administração.
     */
    public function index()
    {
        // 1. Busca TODAS as reclamações, com os dados do usuário relacionado (Eager Loading)
        $complaints = Complaint::latest()->with('user')->get();

        // 2. Envia os dados para a view
        return view('admin.dashboard', ['complaints' => $complaints]);
    }

    /**
     * Atualiza o status de uma reclamação específica.
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        // 1. Valida os dados recebidos (status E o novo comentário)
        $request->validate([
            'status' => 'required|in:Aberto,Em Análise,Resolvido',
            'admin_comment' => 'nullable|string|max:2000' // Adicionado
        ]);

        // 2. Atualiza o status e o novo comentário
        $complaint->status = $request->status;
        $complaint->admin_comment = $request->admin_comment; // Adicionado
        $complaint->save(); // 3. Salva a alteração

        // --- INÍCIO DA ADIÇÃO DO E-MAIL ---
        
        // 1. Precisamos garantir que a informação do usuário esteja carregada
        $complaint->load('user');

        // 2. Verificamos se a reclamação tem um usuário associado antes de tentar enviar
        if ($complaint->user) {
            // Envia o e-mail para o dono da reclamação
            Mail::to($complaint->user)->send(new ComplaintStatusUpdated($complaint));
        }
        
        // --- FIM DA ADIÇÃO DO E-MAIL ---

        // 4. Redireciona de volta
        return redirect()->route('admin.dashboard')->with('success', 'Status da reclamação #' . $complaint->id . ' atualizado com sucesso!');
    }

    /**
     * Retorna os detalhes de uma reclamação específica em formato JSON.
     */
    public function getComplaintDetails(Complaint $complaint)
    {
        // Carrega a informação do usuário junto com a reclamação
        $complaint->load('user');

        // Retorna os dados da reclamação (e do usuário) em formato JSON
        return response()->json($complaint);
    }
}