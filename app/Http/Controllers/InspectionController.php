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
        $inspection->update([
            'documento_numero' => sprintf('%04d%04d', random_int(1000, 9999), $inspection->id)
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
        $isAutosave = $request->header('X-Autosave') === '1';

        if ($isAutosave) {
            $validated = $request->validate([
                'endereco' => 'nullable|string|max:255',
                'cep' => 'nullable|string|max:9',
                'logradouro' => 'nullable|string|max:255',
                'numero' => 'nullable|string|max:20',
                'complemento' => 'nullable|string|max:100',
                'bairro' => 'nullable|string|max:100',
                'cidade' => 'nullable|string|max:100',
                'uf' => 'nullable|string|max:2',
                'responsavel' => 'nullable|string|max:255',
                'locatario_nome' => 'nullable|string|max:255',
                'data_vistoria' => 'nullable|date',
            ]);
            $data = [
                'endereco' => $validated['endereco'] ?? null,
                'cep' => !empty($validated['cep']) ? preg_replace('/\D/', '', $validated['cep']) : null,
                'logradouro' => $validated['logradouro'] ?? null,
                'numero' => $validated['numero'] ?? null,
                'complemento' => $validated['complemento'] ?? null,
                'bairro' => $validated['bairro'] ?? null,
                'cidade' => $validated['cidade'] ?? null,
                'uf' => !empty($validated['uf']) ? strtoupper(substr($validated['uf'], 0, 2)) : null,
                'responsavel' => $validated['responsavel'] ?? null,
                'locatario_nome' => $validated['locatario_nome'] ?? null,
                'data_vistoria' => !empty($validated['data_vistoria']) ? $validated['data_vistoria'] : $inspection->data_vistoria,
            ];
        } else {
            $validated = $request->validate([
                'endereco' => 'required|string|max:255',
                'cep' => 'nullable|string|max:9',
                'logradouro' => 'nullable|string|max:255',
                'numero' => 'nullable|string|max:20',
                'complemento' => 'nullable|string|max:100',
                'bairro' => 'nullable|string|max:100',
                'cidade' => 'nullable|string|max:100',
                'uf' => 'nullable|string|max:2',
                'responsavel' => 'required|string|max:255',
                'locatario_nome' => 'nullable|string|max:255',
                'data_vistoria' => 'required|date',
            ], [
                'endereco.required' => 'Informe o imóvel vistoriado (ex: Apto 101, Casa 2).',
                'responsavel.required' => 'Informe o responsável pela vistoria.',
                'data_vistoria.required' => 'Informe a data e hora da vistoria.',
                'data_vistoria.date' => 'Data da vistoria inválida.',
            ]);
            $data = [
                'endereco' => $validated['endereco'],
                'cep' => !empty($validated['cep']) ? preg_replace('/\D/', '', $validated['cep']) : null,
                'logradouro' => $validated['logradouro'] ?? null,
                'numero' => $validated['numero'] ?? null,
                'complemento' => $validated['complemento'] ?? null,
                'bairro' => $validated['bairro'] ?? null,
                'cidade' => $validated['cidade'] ?? null,
                'uf' => !empty($validated['uf']) ? strtoupper(substr($validated['uf'], 0, 2)) : null,
                'responsavel' => $validated['responsavel'],
                'locatario_nome' => $validated['locatario_nome'] ?? null,
                'data_vistoria' => $validated['data_vistoria'],
            ];
        }

        $inspection->fill($data);
        if ($inspection->logradouro || $inspection->cep) {
            $inspection->endereco_completo = $inspection->endereco_formatado;
        }
        $inspection->save();

        if ($isAutosave) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('inspections.items', $inspection)
            ->with('success', 'Dados salvos. Agora adicione os itens por ambiente.');
    }

    public function items(Inspection $inspection)
    {
        $inspection->load(['items.photos']);
        $draftItem = $inspection->items()->where('is_draft', true)->latest()->first();
        return view('inspections.items', compact('inspection', 'draftItem'));
    }

    public function storeItem(Request $request, Inspection $inspection)
    {
        $validated = $request->validate([
            'categoria' => 'nullable|string',
            'item' => 'required|string',
            'marca_modelo' => 'nullable|string',
            'localizacao' => 'required|string',
            'estado_fisico' => 'required|in:Novo,Seminovo,Ótimo,Bom,Regular,Não se aplica',
            'funcionamento' => 'required|in:Funcionando perfeitamente,Funcionando,Funcionando com ressalvas,Não testado,Não funciona,Não se aplica',
            'observacoes' => 'nullable|string',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|max:5120'
        ]);

        $validated['foto'] = null;
        // Só é rascunho se veio do autosave (item placeholder). Salvamento direto = item finalizado.
        $validated['is_draft'] = in_array(trim($validated['item'] ?? ''), ['', '(em preenchimento)'], true);
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
            'message' => 'Item adicionado com sucesso!',
            'id' => $item->id
        ]);
    }

    public function updateItem(Request $request, InspectionItem $item)
    {
        $isAutosave = $request->header('X-Autosave') === '1';

        if ($isAutosave) {
            $validated = $request->validate([
                'categoria' => 'nullable|string',
                'item' => 'nullable|string',
                'marca_modelo' => 'nullable|string',
                'localizacao' => 'nullable|string',
                'estado_fisico' => 'nullable|in:Novo,Seminovo,Ótimo,Bom,Regular,Não se aplica',
                'funcionamento' => 'nullable|in:Funcionando perfeitamente,Funcionando,Funcionando com ressalvas,Não testado,Não funciona,Não se aplica',
                'observacoes' => 'nullable|string',
                'fotos' => 'nullable|array',
                'fotos.*' => 'image|max:5120'
            ]);
            $itemVal = trim($validated['item'] ?? '');
            $estadoVal = $validated['estado_fisico'] ?? '';
            $funcVal = $validated['funcionamento'] ?? '';
            $item->fill([
                'categoria' => $validated['categoria'] ?? null,
                'item' => $itemVal !== '' ? $itemVal : '(em preenchimento)',
                'marca_modelo' => $validated['marca_modelo'] ?? null,
                'localizacao' => $validated['localizacao'] ?? $item->localizacao,
                'estado_fisico' => $estadoVal !== '' ? $estadoVal : 'Não se aplica',
                'funcionamento' => $funcVal !== '' ? $funcVal : 'Não se aplica',
                'observacoes' => $validated['observacoes'] ?? null,
                'is_draft' => true,
            ]);
        } else {
            // Salvamento manual (botão Adicionar item / Salvar alterações): sempre marcar como item finalizado
            $validated = $request->validate([
                'categoria' => 'nullable|string',
                'item' => 'required|string',
                'marca_modelo' => 'nullable|string',
                'localizacao' => 'required|string',
                'estado_fisico' => 'required|in:Novo,Seminovo,Ótimo,Bom,Regular,Não se aplica',
                'funcionamento' => 'required|in:Funcionando perfeitamente,Funcionando,Funcionando com ressalvas,Não testado,Não funciona,Não se aplica',
                'observacoes' => 'nullable|string',
                'fotos' => 'nullable|array',
                'fotos.*' => 'image|max:5120'
            ]);
            $item->fill([
                'categoria' => $validated['categoria'] ?? null,
                'item' => $validated['item'],
                'marca_modelo' => $validated['marca_modelo'] ?? null,
                'localizacao' => $validated['localizacao'],
                'estado_fisico' => $validated['estado_fisico'],
                'funcionamento' => $validated['funcionamento'],
                'observacoes' => $validated['observacoes'] ?? null,
                'is_draft' => false,
            ]);
        }
        $item->save();

        $files = $request->file('fotos');
        if ($isAutosave) {
            // No autosave, se vierem fotos, substituímos o conjunto para evitar duplicação.
            if ($files !== null) {
                $item->load('photos');
                foreach ($item->photos as $photo) {
                    Storage::disk('public')->delete($photo->path);
                }
                $item->photos()->delete();
                $item->update(['foto' => null]);

                $firstPath = null;
                foreach ($files as $file) {
                    $path = $file->store('fotos', 'public');
                    $item->photos()->create(['path' => $path]);
                    if ($firstPath === null) {
                        $firstPath = $path;
                    }
                }
                if ($firstPath) {
                    $item->update(['foto' => $firstPath]);
                }
            }
        } else {
            // Em edição manual, permite manter/remover fotos existentes e adicionar novas.
            $shouldUpdatePhotos =
                $request->has('keep_photo_ids') ||
                $request->has('remove_legacy_foto') ||
                ($files !== null);

            if ($shouldUpdatePhotos) {
                $item->load('photos');

                $keepIds = [];
                if ($request->has('keep_photo_ids')) {
                    $keepIdsRaw = $request->input('keep_photo_ids', []);
                    $keepIds = array_values(array_unique(array_filter(array_map('intval', (array) $keepIdsRaw))));
                }

                foreach ($item->photos as $photo) {
                    if (!in_array((int) $photo->id, $keepIds, true)) {
                        Storage::disk('public')->delete($photo->path);
                        $photo->delete();
                    }
                }

                $removeLegacy = $request->boolean('remove_legacy_foto');
                $hasOnlyLegacyFoto = $item->photos()->count() === 0;
                if ($removeLegacy && $hasOnlyLegacyFoto && $item->foto) {
                    Storage::disk('public')->delete($item->foto);
                    $item->foto = null;
                    $item->save();
                }

                if ($files) {
                    foreach ($files as $file) {
                        $path = $file->store('fotos', 'public');
                        $item->photos()->create(['path' => $path]);
                    }
                }

                $firstPath = $item->photos()->orderBy('id')->value('path');
                $item->update(['foto' => $firstPath ?: null]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Item atualizado com sucesso!'
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
