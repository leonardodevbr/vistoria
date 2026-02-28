<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspectionController;

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $senha = 'vistoria2024';
    
    if ($request->input('senha') === $senha) {
        session(['autenticado' => true]);
        return redirect('/');
    }
    
    return redirect('/login')->with('erro', 'Senha incorreta!');
});

Route::get('/logout', function () {
    session()->forget('autenticado');
    return redirect('/login')->with('success', 'Logout realizado com sucesso!');
})->name('logout');

Route::middleware(['simple.password'])->group(function () {
    Route::get('/', [InspectionController::class, 'index'])->name('inspections.index');
    Route::post('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
    Route::get('/inspections/{inspection}/edit', [InspectionController::class, 'edit'])->name('inspections.edit');
    Route::put('/inspections/{inspection}', [InspectionController::class, 'update'])->name('inspections.update');
    Route::post('/inspections/{inspection}/items', [InspectionController::class, 'storeItem'])->name('inspections.items.store');
    Route::delete('/inspections/items/{item}', [InspectionController::class, 'deleteItem'])->name('inspections.items.delete');
    Route::get('/inspections/{inspection}/pdf', [InspectionController::class, 'generatePdf'])->name('inspections.pdf');
    Route::delete('/inspections/{inspection}', [InspectionController::class, 'destroy'])->name('inspections.destroy');
});
