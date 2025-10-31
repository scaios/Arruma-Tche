<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintSubmitted;

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
        // 1. VALIDAR TODOS os dados do formulário
        $validatedData = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // 10MB
            'neighborhood' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 2. Lógica para salvar a foto (se ela existir)
        if ($request->hasFile('photo')) {
            // Salva a foto na pasta 'storage/app/public/complaint_photos'
            // O link simbólico torna acessível via '/storage/complaint_photos'
            $path = $request->file('photo')->store('complaint_photos', 'public');
            $validatedData['photo_path'] = $path;
        }

        // 3. Adicionar os dados que o usuário não preenche
        $validatedData['user_id'] = auth()->id();
        $validatedData['status'] = 'Aberto';

        // 4. Criar a reclamação com TODOS os dados
        $complaint = Complaint::create($validatedData);

        // 5. Preparar e enviar o e-mail
        $complaint->load('user');
        Mail::to(auth()->user())->send(new ComplaintSubmitted($complaint));
        
        // 6. Redirecionar com sucesso
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
        if (auth()->id() !== $complaint->user_id) {
            return response()->json(['error' => 'Acesso não autorizado.'], 403);
        }
        return response()->json($complaint);
    }

    /**
     * Atualiza uma reclamação existente no banco de dados.
     */
    public function update(Request $request, Complaint $complaint)
    {
        if (auth()->id() !== $complaint->user_id) {
            abort(403, 'Acesso não autorizado.');
        }
        if ($complaint->status !== 'Aberto') {
            return redirect()->route('complaints.my')->with('error', 'Reclamações em análise ou resolvidas não podem ser editadas.');
        }
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'address' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
        ]);

        $complaint->update($validatedData);

        return redirect()->route('complaints.my')->with('success', 'Reclamação #' . $complaint->id . ' atualizada com sucesso!');
    }

    /**
     * Remove uma reclamação específica do banco de dados.
     */
    public function destroy(Complaint $complaint)
    {
        if (auth()->id() !== $complaint->user_id) {
            abort(403, 'Acesso não autorizado.');
        }
        if ($complaint->status !== 'Aberto') {
            return response()->json(['error' => 'Reclamações em análise ou resolvidas não podem ser excluídas.'], 422);
        }
        
        $complaint->delete();

        return response()->json(['success' => 'Reclamação #' . $complaint->id . ' excluída com sucesso!']);
    }
}