<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\InspectionController;
use App\Models\User;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required',
    ]);

    $user = User::where('username', $request->username)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return redirect()->route('login')->with('erro', 'Usuário ou senha incorretos.');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login')->with('success', 'Logout realizado com sucesso.');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [InspectionController::class, 'index'])->name('inspections.index');
    Route::post('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
    Route::get('/inspections/{inspection}/edit', [InspectionController::class, 'edit'])->name('inspections.edit');
    Route::put('/inspections/{inspection}', [InspectionController::class, 'update'])->name('inspections.update');
    Route::get('/inspections/{inspection}/items', [InspectionController::class, 'items'])->name('inspections.items');
    Route::post('/inspections/{inspection}/items', [InspectionController::class, 'storeItem'])->name('inspections.items.store');
    Route::put('/inspections/items/{item}', [InspectionController::class, 'updateItem'])->name('inspections.items.update');
    Route::delete('/inspections/items/{item}', [InspectionController::class, 'deleteItem'])->name('inspections.items.delete');
    Route::get('/inspections/{inspection}/pdf', [InspectionController::class, 'generatePdf'])->name('inspections.pdf');
    Route::delete('/inspections/{inspection}', [InspectionController::class, 'destroy'])->name('inspections.destroy');
});
