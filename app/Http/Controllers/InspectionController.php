<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\InspectionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
    public function index()
    {
        $inspections = Inspection::with('items')->latest()->get();
        return view('inspections.index', compact('inspections'));
    }

    public function create()
    {
        $inspection = Inspection::create([
            'data_vistoria' => now()
        ]);
        
        return redirect()->route('inspections.edit', $inspection);
    }

    public function edit(Inspection $inspection)
    {
        $inspection->load('items');
        return view('inspections.form', compact('inspection'));
    }

    public function update(Request $request, Inspection $inspection)
    {
        $inspection->update([
            'endereco' => $request->endereco,
            'responsavel' => $request->responsavel,
            'data_vistoria' => $request->data_vistoria ?? now()
        ]);

        return redirect()->route('inspections.edit', $inspection)
            ->with('success', 'Vistoria atualizada com sucesso!');
    }

    public function storeItem(Request $request, Inspection $inspection)
    {
        $validated = $request->validate([
            'categoria' => 'nullable|string',
            'item' => 'required|string',
            'marca_modelo' => 'nullable|string',
            'localizacao' => 'required|string',
            'estado_fisico' => 'required|in:Novo,Seminovo,Ótimo,Bom,Regular',
            'funcionamento' => 'required|in:Funcionando perfeitamente,Funcionando,Funcionando com ressalvas,Não testado,Não funciona,Não se aplica',
            'observacoes' => 'nullable|string',
            'foto' => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos', 'public');
            $validated['foto'] = $path;
        }

        $inspection->items()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item adicionado com sucesso!'
        ]);
    }

    public function deleteItem(InspectionItem $item)
    {
        if ($item->foto) {
            Storage::disk('public')->delete($item->foto);
        }
        
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removido com sucesso!'
        ]);
    }

    public function generatePdf(Inspection $inspection)
    {
        $inspection->load('items');
        
        $pdf = Pdf::loadView('inspections.pdf', compact('inspection'));
        
        return $pdf->download('vistoria-' . $inspection->id . '.pdf');
    }

    public function destroy(Inspection $inspection)
    {
        foreach ($inspection->items as $item) {
            if ($item->foto) {
                Storage::disk('public')->delete($item->foto);
            }
        }
        
        $inspection->delete();

        return redirect()->route('inspections.index')
            ->with('success', 'Vistoria excluída com sucesso!');
    }
}
