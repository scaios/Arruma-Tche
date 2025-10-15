<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint; // Importa o nosso Model de Reclamação

class DashboardController extends Controller
{
    /**
     * Exibe o painel principal de administração.
     * Este método busca todas as reclamações e as envia para a view.
     */
    public function index()
    {
        // 1. Busca TODAS as reclamações, ordenando das mais novas para as mais antigas.
        // Ao contrário do outro controller, aqui não filtramos por usuário.
        $complaints = Complaint::latest()->get();

        // 2. Envia os dados para uma nova view que vamos criar.
        return view('admin.dashboard', ['complaints' => $complaints]);
    }

    /**
     * Atualiza o status de uma reclamação específica.
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        // 1. Valida os dados recebidos. Garante que o status é um dos valores permitidos.
        $request->validate([
            'status' => 'required|in:Aberto,Em Análise,Resolvido',
        ]);

        // 2. Atualiza o campo 'status' da reclamação com o valor vindo do formulário.
        $complaint->status = $request->status;
        $complaint->save(); // 3. Salva a alteração no banco de dados.

        // 4. Redireciona o admin de volta para o dashboard com uma mensagem de sucesso.
        return redirect()->route('admin.dashboard')->with('success', 'Status da reclamação #' . $complaint->id . ' atualizado com sucesso!');
    }

    /**
     * Retorna os detalhes de uma reclamação específica em formato JSON.
     */
    public function getComplaintDetails(Complaint $complaint)
    {
        // Esta função não retorna uma view (página HTML).
        // Ela retorna os dados da reclamação em formato JSON,
        // que é perfeito para o JavaScript consumir.
        return response()->json($complaint);
    }
}