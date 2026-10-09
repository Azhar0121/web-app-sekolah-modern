<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        $rooms = Room::orderBy('code')->paginate(15);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:rooms,code'],
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:kelas,laboratorium,perpustakaan,aula,lapangan'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['nullable', 'boolean'],
            'photo'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'gallery'     => ['nullable', 'array'],
            'gallery.*'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('rooms/photos', 'public');
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $item) {
                if ($item->isValid()) {
                    $galleryPaths[] = $item->store('rooms/gallery', 'public');
                }
            }
        }

        $room = Room::create([
            'code'        => strtoupper($validated['code']),
            'name'        => $validated['name'],
            'type'        => $validated['type'],
            'capacity'    => $validated['capacity'] ?? null,
            'location'    => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'photo'       => $photoPath,
            'gallery'     => !empty($galleryPaths) ? $galleryPaths : null,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        AuditLog::log('create', "Menambahkan ruangan/fasilitas baru: {$room->name}", $room);

        return back()->with('success', 'Data ruangan/fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'code'             => ['required', 'string', 'max:20', 'unique:rooms,code,' . $room->id],
            'name'             => ['required', 'string', 'max:255'],
            'type'             => ['required', 'in:kelas,laboratorium,perpustakaan,aula,lapangan'],
            'capacity'         => ['nullable', 'integer', 'min:1'],
            'location'         => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:1000'],
            'is_active'        => ['nullable', 'boolean'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'gallery'          => ['nullable', 'array'],
            'gallery.*'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_photo'     => ['nullable', 'boolean'],
            'delete_gallery'   => ['nullable', 'array'],
            'delete_gallery.*' => ['nullable', 'string'],
        ]);

        $old = $room->toArray();

        $photoPath = $room->photo;
        if ($request->boolean('remove_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = null;
        } elseif ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('rooms/photos', 'public');
        }

        $currentGallery = is_array($room->gallery) ? $room->gallery : [];
        if ($request->filled('delete_gallery')) {
            $toDelete = (array) $request->input('delete_gallery');
            foreach ($toDelete as $delPath) {
                if (in_array($delPath, $currentGallery)) {
                    if (Storage::disk('public')->exists($delPath)) {
                        Storage::disk('public')->delete($delPath);
                    }
                    $currentGallery = array_values(array_filter($currentGallery, fn ($p) => $p !== $delPath));
                }
            }
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $item) {
                if ($item->isValid()) {
                    $currentGallery[] = $item->store('rooms/gallery', 'public');
                }
            }
        }

        $room->update([
            'code'        => strtoupper($validated['code']),
            'name'        => $validated['name'],
            'type'        => $validated['type'],
            'capacity'    => $validated['capacity'] ?? null,
            'location'    => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'photo'       => $photoPath,
            'gallery'     => !empty($currentGallery) ? $currentGallery : null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        AuditLog::log('update', "Memperbarui ruangan: {$room->name}", $room, $old, $room->toArray());

        return back()->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $name = $room->name;

        if ($room->photo && Storage::disk('public')->exists($room->photo)) {
            Storage::disk('public')->delete($room->photo);
        }
        if (is_array($room->gallery)) {
            foreach ($room->gallery as $item) {
                if (Storage::disk('public')->exists($item)) {
                    Storage::disk('public')->delete($item);
                }
            }
        }

        $room->delete();

        AuditLog::log('delete', "Menghapus ruangan: {$name}");

        return back()->with('success', 'Data ruangan berhasil dihapus.');
    }
}
