<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        ]);

        $room = Room::create([
            'code'        => strtoupper($validated['code']),
            'name'        => $validated['name'],
            'type'        => $validated['type'],
            'capacity'    => $validated['capacity'] ?? null,
            'location'    => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        AuditLog::log('create', "Menambahkan ruangan/fasilitas baru: {$room->name}", $room);

        return back()->with('success', 'Data ruangan/fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:rooms,code,' . $room->id],
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:kelas,laboratorium,perpustakaan,aula,lapangan'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $old = $room->toArray();

        $room->update([
            'code'        => strtoupper($validated['code']),
            'name'        => $validated['name'],
            'type'        => $validated['type'],
            'capacity'    => $validated['capacity'] ?? null,
            'location'    => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        AuditLog::log('update', "Memperbarui ruangan: {$room->name}", $room, $old, $room->toArray());

        return back()->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $name = $room->name;
        $room->delete();

        AuditLog::log('delete', "Menghapus ruangan: {$name}");

        return back()->with('success', 'Data ruangan berhasil dihapus.');
    }
}
