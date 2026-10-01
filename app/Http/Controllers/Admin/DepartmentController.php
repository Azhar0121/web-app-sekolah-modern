<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::orderBy('code')->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:departments,code'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $department = Department::create([
            'code'        => strtoupper($validated['code']),
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        AuditLog::log('create', "Menambahkan jurusan baru: {$department->name}", $department);

        return back()->with('success', 'Jurusan / Program Keahlian berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:20', 'unique:departments,code,' . $department->id],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $old = $department->toArray();

        $department->update([
            'code'        => strtoupper($validated['code']),
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        AuditLog::log('update', "Memperbarui jurusan: {$department->name}", $department, $old, $department->toArray());

        return back()->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $name = $department->name;
        $department->delete();

        AuditLog::log('delete', "Menghapus jurusan: {$name}");

        return back()->with('success', 'Jurusan berhasil dihapus.');
    }
}
