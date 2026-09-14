<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Correspondence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CorrespondenceController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type', 'masuk');
        $type = in_array($type, ['masuk', 'keluar']) ? $type : 'masuk';

        $search = $request->string('search')->trim()->toString();

        $correspondences = Correspondence::with('creator')
            ->where('type', $type)
            ->when($search, fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->where('subject', 'like', "%{$search}%")
                    ->orWhere('correspondent', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%");
            }))
            ->orderByDesc('letter_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'masuk' => Correspondence::where('type', 'masuk')->count(),
            'keluar' => Correspondence::where('type', 'keluar')->count(),
        ];

        return view('admin.correspondences.index', compact('correspondences', 'type', 'search', 'counts'));
    }

    public function create(Request $request): View
    {
        $type = $request->query('type', 'masuk');
        $type = in_array($type, ['masuk', 'keluar']) ? $type : 'masuk';

        return view('admin.correspondences.create', [
            'type' => $type,
            'statusOptions' => Correspondence::statusOptions($type),
            'categoryOptions' => Correspondence::categoryOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $type = $request->input('type');
        abort_unless(in_array($type, ['masuk', 'keluar']), 422, 'Jenis surat tidak valid.');

        $validated = $this->validateCorrespondence($request, $type);

        $correspondence = new Correspondence($validated);
        $correspondence->type = $type;
        $correspondence->number = Correspondence::generateNumber($type, new \DateTime($validated['letter_date']));
        $correspondence->created_by = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $correspondence->file_path = $file->store('persuratan/'.$type, 'public');
            $correspondence->file_original_name = $file->getClientOriginalName();
        }

        $correspondence->save();

        return redirect()
            ->route('admin.correspondences.index', ['type' => $type])
            ->with('success', "Surat berhasil dicatat dengan nomor {$correspondence->number}.");
    }

    public function show(Correspondence $correspondence): View
    {
        $correspondence->load('creator');

        return view('admin.correspondences.show', compact('correspondence'));
    }

    public function edit(Correspondence $correspondence): View
    {
        return view('admin.correspondences.edit', [
            'correspondence' => $correspondence,
            'statusOptions' => Correspondence::statusOptions($correspondence->type),
            'categoryOptions' => Correspondence::categoryOptions(),
        ]);
    }

    public function update(Request $request, Correspondence $correspondence): RedirectResponse
    {
        // Jenis surat & nomor bersifat permanen setelah dibuat, supaya urutan
        // penomoran resmi tidak berubah-ubah / bolong tanpa jejak.
        $validated = $this->validateCorrespondence($request, $correspondence->type);

        $correspondence->fill($validated);

        if ($request->hasFile('file')) {
            if ($correspondence->file_path) {
                Storage::disk('public')->delete($correspondence->file_path);
            }
            $file = $request->file('file');
            $correspondence->file_path = $file->store('persuratan/'.$correspondence->type, 'public');
            $correspondence->file_original_name = $file->getClientOriginalName();
        } elseif ($request->boolean('remove_file')) {
            if ($correspondence->file_path) {
                Storage::disk('public')->delete($correspondence->file_path);
            }
            $correspondence->file_path = null;
            $correspondence->file_original_name = null;
        }

        $correspondence->save();

        return redirect()
            ->route('admin.correspondences.show', $correspondence)
            ->with('success', 'Data surat berhasil diperbarui.');
    }

    public function destroy(Correspondence $correspondence): RedirectResponse
    {
        $type = $correspondence->type;

        if ($correspondence->file_path) {
            Storage::disk('public')->delete($correspondence->file_path);
        }
        $correspondence->delete();

        return redirect()
            ->route('admin.correspondences.index', ['type' => $type])
            ->with('success', 'Data surat berhasil dihapus dari arsip.');
    }

    private function validateCorrespondence(Request $request, string $type): array
    {
        $validated = $request->validate([
            'letter_date' => ['required', 'date'],
            'category' => ['required', Rule::in(Correspondence::categoryOptions())],
            'subject' => ['required', 'string', 'max:255'],
            'correspondent' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'disposition' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(array_keys(Correspondence::statusOptions($type)))],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ]);

        // Disposisi cuma relevan untuk surat masuk
        if ($type === 'keluar') {
            $validated['disposition'] = null;
        }

        return $validated;
    }
}
