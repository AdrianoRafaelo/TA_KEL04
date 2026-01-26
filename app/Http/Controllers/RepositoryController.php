<?php

namespace App\Http\Controllers;

use App\Models\RepositoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RepositoryController extends Controller
{
    public function index()
    {
        $items = RepositoryItem::paginate(10);
        return view('repository.index', compact('items'));
    }

    public function search(Request $request)
    {
        $query = RepositoryItem::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('author')) {
            $query->where('author', 'like', '%' . $request->author . '%');
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        $items = $query->paginate(10)->appends($request->query());
        return view('repository.index', compact('items'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('repository', $fileName, 'public');

        RepositoryItem::create([
            'type' => 'manual',
            'title' => $request->title,
            'author' => $request->author,
            'year' => $request->year,
            'file_path' => $filePath,
            'description' => $request->description,
            'category' => $request->category,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diupload ke repository.');
    }

    public function download($id)
    {
        $item = RepositoryItem::findOrFail($id);

        if (!Storage::disk('public')->exists($item->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($item->file_path, basename($item->file_path));
    }

    public function edit($id)
    {
        $item = RepositoryItem::findOrFail($id);

        return view('repository.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = RepositoryItem::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'year' => $request->year,
            'description' => $request->description,
            'category' => $request->category,
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('public')->exists($item->file_path)) {
                Storage::disk('public')->delete($item->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('repository', $fileName, 'public');
            $data['file_path'] = $filePath;
        }

        $item->update($data);

        return redirect()->route('repository.index')->with('success', 'Dokumen berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = RepositoryItem::findOrFail($id);

        // Delete file
        if (Storage::disk('public')->exists($item->file_path)) {
            Storage::disk('public')->delete($item->file_path);
        }

        $item->delete();

        return redirect()->route('repository.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function bulkAddFromTa(Request $request)
    {
        $request->validate([
            'skripsi_ids' => 'required|array',
            'skripsi_ids.*' => 'integer|exists:ta_skripsis,id',
        ]);

        $count = 0;
        foreach ($request->skripsi_ids as $skripsiId) {
            $skripsi = \App\Models\TaSkripsi::find($skripsiId);
            if ($skripsi) {
                $user = \App\Models\User::where('nim', $skripsi->mahasiswa)->first();
                $title = optional($skripsi->mahasiswaTugasAkhir)->judul ?? 'Skripsi';

                if ($skripsi->file_skripsi_pdf) {
                    \App\Models\RepositoryItem::updateOrCreate(
                        ['file_path' => $skripsi->file_skripsi_pdf],
                        [
                            'type' => 'ta',
                            'title' => $title . ' (PDF)',
                            'author' => $skripsi->mahasiswa,
                            'year' => date('Y'),
                            'file_path' => $skripsi->file_skripsi_pdf,
                            'description' => 'File skripsi dalam format PDF',
                            'category' => 'Skripsi',
                            'user_id' => $user ? $user->id : null,
                        ]
                    );
                    $count++;
                }

                if ($skripsi->file_skripsi_word) {
                    \App\Models\RepositoryItem::updateOrCreate(
                        ['file_path' => $skripsi->file_skripsi_word],
                        [
                            'type' => 'ta',
                            'title' => $title . ' (Word)',
                            'author' => $skripsi->mahasiswa,
                            'year' => date('Y'),
                            'file_path' => $skripsi->file_skripsi_word,
                            'description' => 'File skripsi dalam format Word',
                            'category' => 'Skripsi',
                            'user_id' => $user ? $user->id : null,
                        ]
                    );
                    $count++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} dokumen berhasil ditambahkan ke repository.",
        ]);
    }
}
