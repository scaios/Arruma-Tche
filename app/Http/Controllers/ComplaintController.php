<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    /**
     * Exibe a página inicial com as últimas reclamações e estatísticas.
     */
    public function index()
    {
        $complaints = \App\Models\Complaint::latest()->take(10)->get();
        $resolvedCount = \App\Models\Complaint::where('status', 'Resolvido')->count();
        $openCount = \App\Models\Complaint::where('status', '!=', 'Resolvido')->count();

        return view('home', [
            'complaints' => $complaints,
            'resolvedCount' => $resolvedCount,
            'openCount' => $openCount,
        ]);
    }

    /**
     * Mostra o formulário para criar uma nova reclamação.
     */
    public function create()
    {
        return view('complaints.create');
    }

    /**
     * Armazena uma nova reclamação no banco de dados.
     */
    public function store(Request $request)
{
    // 1. Validação
    $validatedData = $request->validate([
        'category' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        'neighborhood' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'contact_name' => 'required|string|max:255',
        'contact_email' => 'required|email|max:255',
        'contact_phone' => 'required|string|max:20',
    ]);

    $photoPath = null;
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('complaints_photos', 'public');
    }

    // 3. Criação do registro
    $complaint = new Complaint();
    $complaint->category = $validatedData['category'];
    $complaint->title = $validatedData['title'];
    $complaint->description = $validatedData['description'];
    $complaint->photo_path = $photoPath;
    $complaint->neighborhood = $validatedData['neighborhood'];
    $complaint->address = $validatedData['address'];
    $complaint->latitude = $validatedData['latitude']; // <-- Linha crucial
    $complaint->longitude = $validatedData['longitude']; // <-- Linha crucial
    $complaint->user_id = auth()->id();
    $complaint->contact_name = $validatedData['contact_name'];
    $complaint->contact_email = $validatedData['contact_email'];
    $complaint->contact_phone = $validatedData['contact_phone'];
    $complaint->save();

    return redirect()->route('complaints.create')->with('success', 'Reclamação enviada com sucesso! Codigo: #' . $complaint->id);
}

    /**
     * Exibe as reclamações feitas pelo usuário autenticado, com filtros.
     */
    public function myComplaints(Request $request)
    {
        $query = \App\Models\Complaint::where('user_id', auth()->id());

        if ($request->filled('category')) { $query->where('category', $request->category); }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('date')) { $query->whereDate('created_at', $request->date); }

        $filteredComplaints = $query->latest()->get();
        $allUserComplaints = \App\Models\Complaint::where('user_id', auth()->id())->get();
        $totalCount = $allUserComplaints->count();
        $resolvedCount = $allUserComplaints->where('status', 'Resolvido')->count();
        $openCount = $totalCount - $resolvedCount;
        
        return view('complaints.my-complaints', [
            'complaints' => $filteredComplaints,
            'totalCount' => $totalCount,
            'resolvedCount' => $resolvedCount,
            'openCount' => $openCount,
        ]);
    }

    /**
     * Mostra os detalhes de uma reclamação específica.
     */
    public function show(\App\Models\Complaint $complaint)
    {
        if (auth()->id() !== $complaint->user_id) { abort(403); }
        return view('complaints.show', ['complaint' => $complaint]);
    }

    /**
     * Retorna os detalhes de uma reclamação específica em formato JSON para o usuário dono.
     */
    public function getDetails(Complaint $complaint)
    {
        // VERIFICAÇÃO DE SEGURANÇA CRUCIAL:
        // Garante que o usuário logado é o mesmo que criou a reclamação.
        if (auth()->id() !== $complaint->user_id) {
            // Se não for o dono, retorna um erro de acesso proibido.
            return response()->json(['error' => 'Acesso não autorizado.'], 403);
        }

        // Se a verificação passar, retorna os dados da reclamação em formato JSON.
        return response()->json($complaint);
    }

/**
     * Atualiza uma reclamação existente no banco de dados.
     */
    public function update(Request $request, Complaint $complaint)
    {
        // 1. VERIFICAÇÃO DE SEGURANÇA: Garante que o usuário só pode editar as próprias reclamações.
        if (auth()->id() !== $complaint->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // 2. VERIFICAÇÃO DE STATUS: Impede a edição se a reclamação já foi resolvida ou está em análise.
        if ($complaint->status !== 'Aberto') {
            return redirect()->route('complaints.my')->with('error', 'Reclamações em análise ou resolvidas não podem ser editadas.');
        }
        
        // 3. Validação dos dados recebidos (similar ao método store).
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'address' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
        ]);

        // 4. Atualiza a reclamação com os dados validados.
        $complaint->update($validatedData);

        // 5. Redireciona de volta para "Minhas Reclamações" com uma mensagem de sucesso.
        return redirect()->route('complaints.my')->with('success', 'Reclamação #' . $complaint->id . ' atualizada com sucesso!');
    }

/**
     * Remove uma reclamação específica do banco de dados.
     */
    public function destroy(Complaint $complaint)
    {
        // 1. VERIFICAÇÃO DE SEGURANÇA: Garante que o usuário só pode excluir as próprias reclamações.
        if (auth()->id() !== $complaint->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // 2. VERIFICAÇÃO DE STATUS: Impede a exclusão se a reclamação já foi resolvida ou está em análise.
        if ($complaint->status !== 'Aberto') {
            // Retorna uma resposta de erro em JSON, pois será o JavaScript que fará esta chamada.
            return response()->json(['error' => 'Reclamações em análise ou resolvidas não podem ser excluídas.'], 422);
        }
        
        // 3. Exclui a reclamação do banco de dados.
        $complaint->delete();

        // 4. Retorna uma resposta de sucesso em JSON para o JavaScript.
        return response()->json(['success' => 'Reclamação #' . $complaint->id . ' excluída com sucesso!']);
    }




}