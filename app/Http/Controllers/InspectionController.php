<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Models\InspectionItemPhoto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InspectionController extends Controller
{
    private function ensureNotApproved(Inspection $inspection): void
    {
        if ($inspection->isAprovado()) {
            abort(403, 'Esta vistoria foi aprovada e não pode mais ser alterada.');
        }
    }

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
        $this->ensureNotApproved($inspection);
        $inspection->load('items');
        return view('inspections.form', compact('inspection'));
    }

    public function update(Request $request, Inspection $inspection)
    {
        $this->ensureNotApproved($inspection);
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
        if ($inspection->isAprovado()) {
            return redirect()->route('inspections.index')->with('erro', 'Esta vistoria foi aprovada e não pode mais ser alterada.');
        }
        $draftItem = $inspection->items()->where('is_draft', true)->latest()->first();
        return view('inspections.items', compact('inspection', 'draftItem'));
    }

    /**
     * Metadados do dispositivo no momento do registro do item.
     */
    private function deviceMetadata(Request $request): array
    {
        $ua = $request->userAgent();
        $deviceInfo = $ua ? substr($ua, 0, 255) : null;

        return array_filter([
            'ip_address' => $request->ip(),
            'user_agent' => $ua,
            'device_info' => $deviceInfo,
            'latitude' => $request->has('latitude') ? $request->input('latitude') : null,
            'longitude' => $request->has('longitude') ? $request->input('longitude') : null,
            'geolocation_accuracy' => $request->input('geolocation_accuracy'),
        ], fn ($v) => $v !== null && $v !== '');
    }

    public function storeItem(Request $request, Inspection $inspection)
    {
        $this->ensureNotApproved($inspection);
        $validated = $request->validate([
            'categoria' => 'nullable|string',
            'item' => 'required|string',
            'marca_modelo' => 'nullable|string',
            'localizacao' => 'required|string',
            'estado_fisico' => 'required|in:Novo,Seminovo,Ótimo,Bom,Regular,Não se aplica',
            'funcionamento' => 'required|in:Funcionando perfeitamente,Funcionando,Funcionando com ressalvas,Não testado,Não funciona,Não se aplica',
            'observacoes' => 'nullable|string',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|max:5120',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'geolocation_accuracy' => 'nullable|numeric',
        ]);

        $validated['foto'] = null;
        // Só é rascunho se veio do autosave (item placeholder). Salvamento direto = item finalizado.
        $validated['is_draft'] = in_array(trim($validated['item'] ?? ''), ['', '(em preenchimento)'], true);
        $validated = array_merge($validated, $this->deviceMetadata($request));
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
        $this->ensureNotApproved($item->inspection);
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
                'fotos.*' => 'image|max:5120',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'geolocation_accuracy' => 'nullable|numeric',
            ]);
            $item->fill(array_merge([
                'categoria' => $validated['categoria'] ?? null,
                'item' => $validated['item'],
                'marca_modelo' => $validated['marca_modelo'] ?? null,
                'localizacao' => $validated['localizacao'],
                'estado_fisico' => $validated['estado_fisico'],
                'funcionamento' => $validated['funcionamento'],
                'observacoes' => $validated['observacoes'] ?? null,
                'is_draft' => false,
            ], $this->deviceMetadata($request)));
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
                    $keepIdsRaw = $request->input('keep_photo_ids');
                    if (is_string($keepIdsRaw)) {
                        $decoded = json_decode($keepIdsRaw, true);
                        $keepIds = is_array($decoded) ? array_values(array_unique(array_filter(array_map('intval', $decoded)))) : [];
                    } else {
                        $keepIds = array_values(array_unique(array_filter(array_map('intval', (array) $keepIdsRaw))));
                    }
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
        $this->ensureNotApproved($item->inspection);
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

    public function approve(Inspection $inspection)
    {
        if ($inspection->isAprovado()) {
            return redirect()->route('inspections.index')->with('erro', 'Esta vistoria já foi aprovada.');
        }
        $conteudo = $inspection->getConteudoParaAssinatura();
        $inspection->aprovado_em = now();
        $inspection->assinatura_hash = hash('sha256', $conteudo);
        $inspection->save();

        // Gera e grava o PDF no storage como cópia imutável do aprovado
        if ($inspection->items()->where('is_draft', false)->exists()) {
            try {
                $inspection->load(['items.photos']);
                [$pdf, $downloadFilename] = $this->buildPdf($inspection);
                $storagePath = 'pdfs/vistoria-' . $inspection->id . '-' . Str::slug(Str::limit(trim($inspection->endereco ?? ''), 50, '') ?: (string) $inspection->id, '') . '.pdf';
                if (! Storage::disk('public')->exists('pdfs')) {
                    Storage::disk('public')->makeDirectory('pdfs');
                }
                $pdf->save($storagePath, 'public');
                $inspection->pdf_path = $storagePath;
                $inspection->save();
            } catch (\Throwable $e) {
                // Falha ao gravar PDF não desfaz a aprovação; usuário pode baixar depois
            }
        }

        return redirect()->route('inspections.index')
            ->with('success', 'Vistoria aprovada. O documento está selado e não poderá mais ser alterado.');
    }

    public function generatePdf(Inspection $inspection)
    {
        $filename = $this->getPdfDownloadFilename($inspection);

        // Se aprovada e já existe PDF gravado, servir o arquivo do storage (imutável)
        if ($inspection->isAprovado() && $inspection->pdf_path && Storage::disk('public')->exists($inspection->pdf_path)) {
            return Storage::disk('public')->download($inspection->pdf_path, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        [$pdf, ] = $this->buildPdf($inspection);
        return $pdf->download($filename);
    }

    /**
     * Nome do arquivo para download (ex.: vistoria-endereco-slug.pdf).
     */
    private function getPdfDownloadFilename(Inspection $inspection): string
    {
        $slug = $inspection->endereco
            ? Str::slug(Str::limit(trim($inspection->endereco), 80, ''))
            : '';
        return ($slug !== '' ? 'vistoria-' . $slug : 'vistoria-' . $inspection->id) . '.pdf';
    }

    /**
     * Monta o PDF (view + numeração de páginas). Retorna [Pdf, nome para download].
     */
    private function buildPdf(Inspection $inspection): array
    {
        $inspection->load(['items.photos']);
        $photosForPdf = $this->buildPhotosForPdf($inspection);
        $generatedAt = Carbon::now('America/Sao_Paulo');
        $assinaturaHash = $inspection->assinatura_hash;

        $pdf = Pdf::loadView('inspections.pdf', [
            'inspection' => $inspection,
            'photosForPdf' => $photosForPdf,
            'generatedAt' => $generatedAt,
            'assinaturaHash' => $assinaturaHash,
        ]);

        try {
            $dompdf = $pdf->getDomPDF();
            $dompdf->setCallbacks([
                [
                    'event' => 'end_document',
                    'f' => function (int $pageNumber, int $pageCount, $canvas, $fontMetrics) {
                        $font = $fontMetrics->getFont($fontMetrics->getOptions()->getDefaultFont(), 'normal')
                            ?: $fontMetrics->getFont('serif', 'normal');
                        if ($font) {
                            $text = "Página {$pageNumber} de {$pageCount}";
                            $canvas->text(297, 820, $text, $font, 9, [0.4, 0.4, 0.4]);
                        }
                    },
                ],
            ]);
        } catch (\Throwable $e) {
            // Se falhar a numeração, o PDF ainda é gerado
        }

        return [$pdf, $this->getPdfDownloadFilename($inspection)];
    }

    /** Lado máximo (px) para imagens no PDF; proporção mantida. */
    private const PDF_IMAGE_MAX_SIDE = 1200;

    /** Qualidade JPEG (1-100) para imagens redimensionadas no PDF. */
    private const PDF_IMAGE_JPEG_QUALITY = 82;

    /**
     * Monta fotos por item com path (redimensionado para PDF), orientação e hash.
     * @return array<int, array{path: string, landscape: bool, hash: string, item_name: string}[]>
     */
    private function buildPhotosForPdf(Inspection $inspection): array
    {
        $byItem = [];
        $itemsPdf = $inspection->items->where('is_draft', false);

        foreach ($itemsPdf as $item) {
            $pathsWithSource = [];
            if ($item->foto) {
                $pathsWithSource[] = ['path' => $item->foto, 'photo_id' => null];
            }
            foreach ($item->photos as $photo) {
                if ($photo->path !== $item->foto) {
                    $pathsWithSource[] = ['path' => $photo->path, 'photo_id' => $photo->id];
                }
            }

            $entries = [];
            foreach ($pathsWithSource as $entry) {
                $path = $entry['path'];
                $fullPath = Storage::disk('public')->path($path);
                $landscape = true;
                $pathForPdf = $path;
                if (file_exists($fullPath) && @getimagesize($fullPath)) {
                    [$w, $h] = getimagesize($fullPath);
                    $landscape = $w >= $h;
                    $pathForPdf = $this->resizeImageForPdf($fullPath, $path) ?: $path;
                }
                $hashInput = $inspection->id . '|' . $item->id . '|' . ($entry['photo_id'] ?? 'foto') . '|' . $path;
                $hash = 'IMG-' . strtoupper(substr(hash('sha256', $hashInput), 0, 10));

                $entries[] = [
                    'path' => $pathForPdf,
                    'landscape' => $landscape,
                    'hash' => $hash,
                    'item_name' => $item->item,
                ];
            }
            $byItem[$item->id] = $this->groupPhotoEntriesIntoRows($entries);
        }

        return $byItem;
    }

    /**
     * Redimensiona imagem para o PDF (lado máximo 1200px, JPEG) e retorna path relativo ao storage/public.
     * Usa cache em storage/app/public/pdf-cache para não reprocessar.
     */
    private function resizeImageForPdf(string $fullPath, string $originalRelativePath): ?string
    {
        if (!function_exists('imagecreatefromstring')) {
            return null;
        }
        $info = @getimagesize($fullPath);
        if ($info === false || !isset($info[0], $info[1])) {
            return null;
        }
        $w = (int) $info[0];
        $h = (int) $info[1];
        $max = self::PDF_IMAGE_MAX_SIDE;
        if ($w <= $max && $h <= $max) {
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg'], true) && ($info[2] ?? 0) === IMAGETYPE_JPEG) {
                return null;
            }
        }
        $cacheDir = Storage::disk('public')->path('pdf-cache');
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        $cacheKey = md5($fullPath . '@' . filemtime($fullPath));
        $cacheRelative = 'pdf-cache/' . $cacheKey . '.jpg';
        $cacheFull = Storage::disk('public')->path($cacheRelative);
        if (file_exists($cacheFull)) {
            return $cacheRelative;
        }
        $blob = @file_get_contents($fullPath);
        if ($blob === false) {
            return null;
        }
        $src = @imagecreatefromstring($blob);
        if ($src === false) {
            return null;
        }
        $newW = $w;
        $newH = $h;
        if ($w > $max || $h > $max) {
            if ($w >= $h) {
                $newW = $max;
                $newH = (int) round($h * ($max / $w));
            } else {
                $newH = $max;
                $newW = (int) round($w * ($max / $h));
            }
        }
        $dst = imagecreatetruecolor($newW, $newH);
        if ($dst === false) {
            imagedestroy($src);
            return null;
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagedestroy($src);
        $ok = imagejpeg($dst, $cacheFull, self::PDF_IMAGE_JPEG_QUALITY);
        imagedestroy($dst);
        if (!$ok) {
            return null;
        }
        return $cacheRelative;
    }

    /**
     * @param array<int, array{path: string, landscape: bool, hash: string, item_name: string}> $entries
     * @return array<int, array{landscape: bool, entries: array}>
     */
    private function groupPhotoEntriesIntoRows(array $entries): array
    {
        $rows = [];
        $i = 0;
        while ($i < count($entries)) {
            $entry = $entries[$i];
            if ($entry['landscape']) {
                $rows[] = ['landscape' => true, 'entries' => [$entry]];
                $i++;
            } else {
                $rowEntries = [$entry];
                $i++;
                if ($i < count($entries) && !$entries[$i]['landscape']) {
                    $rowEntries[] = $entries[$i];
                    $i++;
                }
                $rows[] = ['landscape' => false, 'entries' => $rowEntries];
            }
        }
        return $rows;
    }

    public function destroy(Inspection $inspection)
    {
        $this->ensureNotApproved($inspection);
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
