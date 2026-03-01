<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Models\InspectionItemPhoto;
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
        $data = [
            'endereco' => $request->endereco,
            'cep' => $request->cep ? preg_replace('/\D/', '', $request->cep) : null,
            'logradouro' => $request->logradouro,
            'numero' => $request->numero,
            'complemento' => $request->complemento,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'uf' => $request->uf ? strtoupper(substr($request->uf, 0, 2)) : null,
            'responsavel' => $request->responsavel,
            'locatario_nome' => $request->locatario_nome,
            'data_vistoria' => $request->filled('data_vistoria') ? $request->data_vistoria : $inspection->data_vistoria
        ];

        $inspection->fill($data);
        if ($inspection->logradouro || $inspection->cep) {
            $inspection->endereco_completo = $inspection->endereco_formatado;
        }
        $inspection->save();

        return redirect()->route('inspections.items', $inspection)
            ->with('success', 'Dados salvos. Agora adicione os itens por ambiente.');
    }

    public function items(Inspection $inspection)
    {
        $inspection->load(['items.photos']);
        return view('inspections.items', compact('inspection'));
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
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|max:5120'
        ]);

        $validated['foto'] = null;
        $item = $inspection->items()->create($validated);

        $files = $request->file('fotos');
        if ($files) {
            foreach ($files as $index => $file) {
                $path = $file->store('fotos', 'public');
                $item->photos()->create(['path' => $path]);
                if ($index === 0) {
                    $item->update(['foto' => $path]);
                }
            }
        }

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
        foreach ($item->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removido com sucesso!'
        ]);
    }

    public function generatePdf(Inspection $inspection)
    {
        $inspection->load(['items.photos']);
        
        $pdf = Pdf::loadView('inspections.pdf', compact('inspection'));
        
        return $pdf->download('vistoria-' . $inspection->id . '.pdf');
    }

    public function destroy(Inspection $inspection)
    {
        foreach ($inspection->items as $item) {
            if ($item->foto) {
                Storage::disk('public')->delete($item->foto);
            }
            foreach ($item->photos as $photo) {
                Storage::disk('public')->delete($photo->path);
            }
        }
        $inspection->delete();

        return redirect()->route('inspections.index')
            ->with('success', 'Vistoria excluída com sucesso!');
    }
}
