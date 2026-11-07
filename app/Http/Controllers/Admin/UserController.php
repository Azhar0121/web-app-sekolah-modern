<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Role::orderBy('name')->get();
        $search = $request->string('search')->trim()->toString();

        if (! $request->has('role')) {
            $roleFilter = $roles->first()?->slug ?? 'all';
        } else {
            $roleFilter = $request->string('role')->toString();
        }

        $users = User::with('role')
            ->when($search, fn ($query) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->when($roleFilter && $roleFilter !== 'all', fn ($query) => $query->whereHas('role', fn ($q) => $q->where('slug', $roleFilter)))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'roles', 'search', 'roleFilter'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUser($request);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users/photos', 'public');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'photo' => $photoPath,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validateUser($request, $user->id);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];
        $user->is_active = $request->boolean('is_active', true);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->boolean('remove_photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
        } elseif ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('users/photos', 'public');
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    private function validateUser(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($ignoreId),
            ],
            'password' => [$ignoreId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);
    }

    public function downloadImportTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-import-guru-tu.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['nama', 'email', 'role']);

            fputcsv($handle, ['Drs. H. Budi Santoso, M.Pd', 'budi.santoso@sekolah.sch.id', 'guru']);
            fputcsv($handle, ['Siti Aminah, S.Pd', 'siti.aminah@sekolah.sch.id', 'guru']);
            fputcsv($handle, ['Rina Wijaya, A.Md', 'rina.wijaya@sekolah.sch.id', 'tu']);

            fclose($handle);
        }, 200, $headers);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'file.required' => 'Silakan pilih file CSV yang akan diunggah.',
            'file.mimes' => 'File harus berformat .csv atau .txt.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if (! $handle) {
            return back()->with('error', 'Gagal membaca file yang diunggah.');
        }

        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $header = fgetcsv($handle, 0, $delimiter);
        if (! $header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau tidak valid.');
        }

        $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
        $header = array_map(fn ($col) => strtolower(trim((string) $col)), $header);

        $nameIndex = array_search('nama', $header);
        if ($nameIndex === false) {
            $nameIndex = array_search('name', $header);
        }

        $emailIndex = array_search('email', $header);
        $roleIndex = array_search('role', $header);

        if ($nameIndex === false || $emailIndex === false || $roleIndex === false) {
            fclose($handle);
            return back()->with('error', 'Format kolom file tidak sesuai. Pastikan ada kolom "nama", "email", dan "role". Silakan gunakan template yang disediakan.');
        }

        $rolesBySlug = Role::whereIn('slug', ['guru', 'tu'])->get()->keyBy('slug');
        if (! $rolesBySlug->has('guru') || ! $rolesBySlug->has('tu')) {
            fclose($handle);
            return back()->with('error', 'Role "guru" atau "tu" belum tersedia di sistem database.');
        }

        $created = [];
        $errors = [];
        $rowNumber = 1; // 1 adalah header

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNumber++;

            if (empty(array_filter($row, fn ($val) => trim((string) $val) !== ''))) {
                continue;
            }

            $name = trim((string) ($row[$nameIndex] ?? ''));
            $email = strtolower(trim((string) ($row[$emailIndex] ?? '')));
            $roleSlug = strtolower(trim((string) ($row[$roleIndex] ?? '')));

            if (empty($name)) {
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => '-',
                    'email' => $email ?: '-',
                    'reason' => 'Nama tidak boleh kosong.',
                ];
                continue;
            }

            if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => $name,
                    'email' => $email ?: '-',
                    'reason' => 'Format email tidak valid.',
                ];
                continue;
            }

            if (! in_array($roleSlug, ['guru', 'tu'], true)) {
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => $name,
                    'email' => $email,
                    'reason' => "Role \"{$roleSlug}\" tidak valid (harus \"guru\" atau \"tu\").",
                ];
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => $name,
                    'email' => $email,
                    'reason' => 'Email sudah terdaftar di sistem.',
                ];
                continue;
            }

            $plainPassword = $this->generateFriendlyPassword($name);
            $roleModel = $rolesBySlug->get($roleSlug);

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($plainPassword),
                'role_id' => $roleModel->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $created[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_name' => $roleModel->name,
                'role_slug' => $roleModel->slug,
                'plain_password' => $plainPassword,
            ];
        }

        fclose($handle);

        if (empty($created)) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Tidak ada akun yang berhasil dibuat. Periksa kembali isi file Anda.')
                ->with('import_errors', $errors);
        }

        session(['latest_import_credentials' => $created]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', count($created) . ' akun Guru & TU berhasil dibuat dengan password acak!')
            ->with('import_success_count', count($created))
            ->with('import_results', $created)
            ->with('import_errors', $errors);
    }

    public function exportRekap(): StreamedResponse|RedirectResponse
    {
        $credentials = session('latest_import_credentials');
        if (empty($credentials) || ! is_array($credentials)) {
            return redirect()->route('admin.users.index')->with('error', 'Data rekap akun import tidak ditemukan atau sesi telah berakhir.');
        }

        $filename = 'rekap-akun-guru-tu-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($credentials) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($handle, ['No', 'Nama Lengkap', 'Email Akun', 'Role', 'Password Sementara', 'Tanggal Dibuat']);

            $no = 1;
            $now = now()->translatedFormat('d F Y H:i');
            foreach ($credentials as $cred) {
                fputcsv($handle, [
                    $no++,
                    $cred['name'] ?? '',
                    $cred['email'] ?? '',
                    $cred['role_name'] ?? $cred['role_slug'] ?? '',
                    $cred['plain_password'] ?? '',
                    $now,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function printSlips(): View|RedirectResponse
    {
        $credentials = session('latest_import_credentials');
        if (empty($credentials) || ! is_array($credentials)) {
            return redirect()->route('admin.users.index')->with('error', 'Data slip akun import tidak ditemukan atau sesi telah berakhir.');
        }

        $schoolName = Setting::get('school_name', config('app.name', 'Sistem Informasi Sekolah'));

        return view('admin.users.print-slips', compact('credentials', 'schoolName'));
    }

    public function generateFriendlyPassword(string $name): string
    {
        $cleanName = preg_replace('/^(drs|dra|dr|hj|haji|h|prof|ir|ust|ustdz)\.?\s+/i', '', trim($name));

        $parts = preg_split('/\s+/', $cleanName);
        $firstWord = preg_replace('/[^a-zA-Z]/', '', $parts[0] ?? 'Guru');

        if (strlen($firstWord) < 4) {
            $prefix = ucfirst(str_pad(strtolower($firstWord), 4, 'k'));
        } else {
            $prefix = ucfirst(strtolower(substr($firstWord, 0, 4)));
        }

        $digits = '';
        $allowed = '23456789';
        $len = strlen($allowed);
        for ($i = 0; $i < 4; $i++) {
            $digits .= $allowed[random_int(0, $len - 1)];
        }

        return $prefix . $digits;
    }
}