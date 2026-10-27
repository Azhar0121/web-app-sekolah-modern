<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $search     = $request->string('search')->trim()->toString();
        $roomId     = $request->query('room_id');
        $categoryId = $request->query('category_id');
        $condition  = $request->query('condition');

        // Statistik ringkas kondisi inventaris
        $totalItems       = InventoryItem::sum('quantity');
        $goodCondition    = InventoryItem::where('condition', 'good')->sum('quantity');
        $lightDamage      = InventoryItem::where('condition', 'light_damage')->sum('quantity');
        $heavyDamage      = InventoryItem::where('condition', 'heavy_damage')->sum('quantity');
        $lostCount        = InventoryItem::where('condition', 'lost')->sum('quantity');

        // Query data item
        $items = InventoryItem::with(['category', 'room', 'creator'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('source_of_fund', 'like', "%{$search}%");
                });
            })
            ->when($roomId, fn ($q) => $q->where('room_id', $roomId))
            ->when($categoryId, fn ($q) => $q->where('inventory_category_id', $categoryId))
            ->when($condition, fn ($q) => $q->where('condition', $condition))
            ->orderBy('room_id')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $rooms      = Room::where('is_active', true)->orderBy('name')->get();
        $categories = InventoryCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.inventory.index', compact(
            'items',
            'rooms',
            'categories',
            'search',
            'roomId',
            'categoryId',
            'condition',
            'totalItems',
            'goodCondition',
            'lightDamage',
            'heavyDamage',
            'lostCount'
        ));
    }

    public function show(InventoryItem $item): View
    {
        $item->load(['category', 'room', 'creator']);
        $rooms      = Room::where('is_active', true)->orderBy('name')->get();
        $categories = InventoryCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.inventory.show', compact('item', 'rooms', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'inventory_category_id' => ['required', 'exists:inventory_categories,id'],
            'room_id'               => ['nullable', 'exists:rooms,id'],
            'quantity'              => ['required', 'integer', 'min:1'],
            'unit'                  => ['required', 'string', 'max:50'],
            'condition'             => ['required', 'in:good,light_damage,heavy_damage,lost'],
            'source_of_fund'        => ['nullable', 'string', 'max:150'],
            'procurement_year'      => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'price_per_unit'        => ['nullable', 'numeric', 'min:0'],
            'photo'                 => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'notes'                 => ['nullable', 'string', 'max:1000'],
        ]);

        $code = InventoryItem::generateCode();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('inventaris', 'public');
        }

        $item = InventoryItem::create([
            'code'                  => $code,
            'name'                  => $validated['name'],
            'inventory_category_id' => $validated['inventory_category_id'],
            'room_id'               => $validated['room_id'] ?? null,
            'quantity'              => $validated['quantity'],
            'unit'                  => $validated['unit'],
            'condition'             => $validated['condition'],
            'source_of_fund'        => $validated['source_of_fund'] ?? null,
            'procurement_year'      => $validated['procurement_year'] ?? null,
            'price_per_unit'        => $validated['price_per_unit'] ?? null,
            'photo_path'            => $photoPath,
            'notes'                 => $validated['notes'] ?? null,
            'created_by'            => auth()->id(),
        ]);

        AuditLog::log('create', "Menambahkan aset inventaris baru: {$item->name} ({$item->code})", $item);

        return back()->with('success', "Aset inventaris {$item->name} berhasil ditambahkan dengan kode {$item->code}.");
    }

    public function update(Request $request, InventoryItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'inventory_category_id' => ['required', 'exists:inventory_categories,id'],
            'room_id'               => ['nullable', 'exists:rooms,id'],
            'quantity'              => ['required', 'integer', 'min:1'],
            'unit'                  => ['required', 'string', 'max:50'],
            'condition'             => ['required', 'in:good,light_damage,heavy_damage,lost'],
            'source_of_fund'        => ['nullable', 'string', 'max:150'],
            'procurement_year'      => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'price_per_unit'        => ['nullable', 'numeric', 'min:0'],
            'photo'                 => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'notes'                 => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->hasFile('photo')) {
            if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
                Storage::disk('public')->delete($item->photo_path);
            }
            $item->photo_path = $request->file('photo')->store('inventaris', 'public');
        }

        $oldValues = $item->only(['name', 'room_id', 'quantity', 'condition', 'notes']);

        $item->update([
            'name'                  => $validated['name'],
            'inventory_category_id' => $validated['inventory_category_id'],
            'room_id'               => $validated['room_id'] ?? null,
            'quantity'              => $validated['quantity'],
            'unit'                  => $validated['unit'],
            'condition'             => $validated['condition'],
            'source_of_fund'        => $validated['source_of_fund'] ?? null,
            'procurement_year'      => $validated['procurement_year'] ?? null,
            'price_per_unit'        => $validated['price_per_unit'] ?? null,
            'notes'                 => $validated['notes'] ?? null,
        ]);

        AuditLog::log('update', "Memperbarui data aset inventaris: {$item->name} ({$item->code})", $item, $oldValues, $item->only(['name', 'room_id', 'quantity', 'condition', 'notes']));

        return back()->with('success', "Data aset {$item->name} berhasil diperbarui.");
    }

    // Mutasi / Pemindahan Ruangan & Perubahan Kondisi Cepat
    public function mutate(Request $request, InventoryItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'room_id'   => ['nullable', 'exists:rooms,id'],
            'condition' => ['required', 'in:good,light_damage,heavy_damage,lost'],
            'notes'     => ['nullable', 'string', 'max:500'],
        ]);

        $oldRoom = $item->room?->name ?? 'Gudang';
        $item->room_id   = $validated['room_id'] ?? null;
        $item->condition = $validated['condition'];

        if (! empty($validated['notes'])) {
            $stamp = date('d/m/Y');
            $item->notes = trim(($item->notes ? $item->notes . "\n" : '') . "[Catatan Mutasi {$stamp}]: " . $validated['notes']);
        }

        $item->save();
        $newRoom = $item->fresh('room')->room?->name ?? 'Gudang';

        AuditLog::log('update', "Mutasi aset {$item->name} ({$item->code}) dari {$oldRoom} ke {$newRoom}, kondisi: {$item->conditionLabel()}", $item);

        return back()->with('success', "Aset {$item->name} berhasil dimutasi ke {$newRoom} (Kondisi: {$item->conditionLabel()}).");
    }

    public function destroy(InventoryItem $item): RedirectResponse
    {
        $name = $item->name;
        $code = $item->code;

        if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
            Storage::disk('public')->delete($item->photo_path);
        }

        $item->delete();

        AuditLog::log('delete', "Menghapus aset inventaris: {$name} ({$code})", null);

        return redirect()->route('admin.inventory.index')->with('success', "Aset inventaris {$name} ({$code}) berhasil dihapus.");
    }
}