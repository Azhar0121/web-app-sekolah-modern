<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MediaFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    public function index(Request $request): View
    {
        $folderFilter = $request->query('folder', 'all');
        $search       = $request->string('search')->trim()->toString();

        $query = MediaFile::with('uploader')
            ->when($folderFilter !== 'all', fn ($q) => $q->where('folder', $folderFilter))
            ->when($search, fn ($q) => $q->where(function ($sub) use ($search) {
                $sub->where('original_name', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%")
                    ->orWhere('caption', 'like', "%{$search}%");
            }));

        $mediaFiles = $query->orderByDesc('created_at')->paginate(24)->withQueryString();
        $folders    = MediaFile::select('folder')->distinct()->pluck('folder');

        return view('admin.media.index', compact('mediaFiles', 'folders', 'folderFilter', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files'    => ['required', 'array'],
            'files.*'  => ['file', 'mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip', 'max:10240'],
            'folder'   => ['nullable', 'string', 'max:50'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $folder = strtolower(trim($request->input('folder', 'general')));
        $uploadedCount = 0;

        foreach ($request->file('files') as $file) {
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path     = $file->storeAs('media/' . $folder, $filename, 'public');
            $mimeType = $file->getClientMimeType();
            $fileSize = $file->getSize();

            $width  = null;
            $height = null;
            if (str_starts_with($mimeType, 'image/') && function_exists('getimagesize')) {
                $imageSize = @getimagesize($file->getRealPath());
                if ($imageSize) {
                    $width  = $imageSize[0];
                    $height = $imageSize[1];
                }
            }

            MediaFile::create([
                'filename'      => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $mimeType,
                'file_path'     => $path,
                'file_size'     => $fileSize,
                'width'         => $width,
                'height'        => $height,
                'folder'        => $folder,
                'alt_text'      => $request->input('alt_text'),
                'uploaded_by'   => auth()->id(),
            ]);

            $uploadedCount++;
        }

        AuditLog::log('create', "Mengunggah {$uploadedCount} file ke Media Library (Folder: {$folder})");

        return back()->with('success', "{$uploadedCount} file berhasil diunggah ke Media Library.");
    }

    public function update(Request $request, MediaFile $media): RedirectResponse
    {
        $validated = $request->validate([
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption'  => ['nullable', 'string', 'max:255'],
            'folder'   => ['nullable', 'string', 'max:50'],
        ]);

        $media->update($validated);

        AuditLog::log('update', "Memperbarui meta info media: {$media->original_name}", $media);

        return back()->with('success', 'Informasi media berhasil diperbarui.');
    }

    public function destroy(MediaFile $media): RedirectResponse
    {
        $filename = $media->original_name;
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        AuditLog::log('delete', "Menghapus file media: {$filename}");

        return back()->with('success', "File media `{$filename}` berhasil dihapus.");
    }
}
