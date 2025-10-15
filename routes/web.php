<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Rotas da Aplicação
|--------------------------------------------------------------------------
*/

// Rota principal para mostrar a lista de reclamações
Route::get('/', [ComplaintController::class, 'index'])->name('home');

// Rota para MOSTRAR o formulário de reclamação
Route::get('/reclamar', [ComplaintController::class, 'create'])->name('complaints.create');

// Rota para SALVAR os dados do formulário
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

// Rota para a página "Sobre"
Route::get('/sobre', function () {
    return view('about');
})->name('about');

// Rota para a página "Minhas Reclamações" (protegida por login)
Route::get('/minhas-reclamacoes', [ComplaintController::class, 'myComplaints'])
     ->middleware(['auth'])
     ->name('complaints.my');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rota para mostrar os detalhes de UMA reclamação específica
Route::get('/reclamacao/{complaint}', [ComplaintController::class, 'show'])
     ->middleware(['auth'])
     ->name('complaints.show');


// =============================================
// ROTAS DO PAINEL DE ADMINISTRAÇÃO
// =============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rota principal do dashboard do admin (Ex: /admin/dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // --- INÍCIO DA ADIÇÃO ---
    // Rota para ATUALIZAR o status de uma reclamação
    // O {complaint} pega o ID da reclamação da URL
    Route::patch('/complaints/{complaint}/status', [DashboardController::class, 'updateStatus'])->name('complaints.updateStatus');
    // --- FIM DA ADIÇÃO ---
    
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/complaints/{complaint}/status', [DashboardController::class, 'updateStatus'])->name('complaints.updateStatus');
    
    // --- INÍCIO DA ADIÇÃO ---
    // Rota para BUSCAR os detalhes de uma reclamação via JavaScript (AJAX)
    Route::get('/complaints/{complaint}/details', [DashboardController::class, 'getComplaintDetails'])->name('complaints.details');
    // --- FIM DA ADIÇÃO ---
});

// Rota para o JavaScript buscar os detalhes de uma reclamação do PRÓPRIO usuário
Route::get('/reclamacoes/{complaint}/detalhes', [ComplaintController::class, 'getDetails'])
     ->middleware(['auth'])
     ->name('complaints.getDetails');

// Rota para ATUALIZAR uma reclamação existente
Route::patch('/reclamacoes/{complaint}', [ComplaintController::class, 'update'])
     ->middleware(['auth'])
     ->name('complaints.update');
     

// Rota para EXCLUIR uma reclamação
Route::delete('/reclamacoes/{complaint}', [ComplaintController::class, 'destroy'])
     ->middleware(['auth'])
     ->name('complaints.destroy');
     
require __DIR__.'/auth.php';