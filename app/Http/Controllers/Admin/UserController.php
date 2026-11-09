<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserImportBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        $latestBatch = UserImportBatch::latest('id')->first();
        $totalBatches = UserImportBatch::count();
        $latestImportCredentials = $latestBatch?->credentials ?? $this->getLatestImportCredentials();

        return view('admin.users.index', compact('users', 'roles', 'search', 'roleFilter', 'latestImportCredentials', 'latestBatch', 'totalBatches'));
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

    public function destroy(Request $request, string $user): RedirectResponse|JsonResponse
    {
        $targetUser = User::find($user);

        if (! $targetUser) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun sudah tidak ditemukan atau telah dihapus sebelumnya.',
                ]);
            }

            return redirect()
                ->route('admin.users.index')
                ->with('info', 'Akun sudah tidak ditemukan atau telah dihapus sebelumnya.');
        }

        if ($targetUser->id === auth()->id()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak bisa menghapus akun Anda sendiri.',
                ], 403);
            }

            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $userName = $targetUser->name;

        if ($targetUser->photo && Storage::disk('public')->exists($targetUser->photo)) {
            Storage::disk('public')->delete($targetUser->photo);
        }

        $targetUser->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "User {$userName} berhasil dihapus.",
            ]);
        }

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

        $batchCode = 'IMP-' . date('Ymd-His');
        UserImportBatch::create([
            'batch_code' => $batchCode,
            'file_name' => $file->getClientOriginalName(),
            'total_accounts' => count($created),
            'credentials' => $created,
            'created_by' => auth()->id(),
        ]);

        $this->saveLatestImportCredentials($created);

        return redirect()
            ->route('admin.users.index')
            ->with('success', count($created) . ' akun Guru & TU berhasil dibuat dengan password acak!')
            ->with('import_success_count', count($created))
            ->with('import_results', $created)
            ->with('import_errors', $errors);
    }

    public function importHistory(): View
    {
        $batches = UserImportBatch::with('creator')->orderByDesc('created_at')->paginate(12);
        $totalAccounts = UserImportBatch::sum('total_accounts');
        $totalBatches = UserImportBatch::count();

        return view('admin.users.import-history', compact('batches', 'totalAccounts', 'totalBatches'));
    }

    public function exportRekap(?UserImportBatch $batch = null): StreamedResponse|RedirectResponse
    {
        if ($batch && $batch->exists) {
            $credentials = $batch->credentials;
            $batchCode = $batch->batch_code;
        } else {
            $sessionCreds = session('latest_import_credentials');
            if (!empty($sessionCreds)) {
                $credentials = $sessionCreds;
                $batchCode = date('Ymd-His');
            } else {
                $latestBatch = UserImportBatch::latest('id')->first();
                $credentials = $latestBatch?->credentials ?? $this->getLatestImportCredentials();
                $batchCode = $latestBatch?->batch_code ?? date('Ymd-His');
            }
        }

        if (empty($credentials)) {
            return redirect()->route('admin.users.index')->with('error', 'Belum ada riwayat akun yang di-import. Silakan lakukan Import Guru & TU terlebih dahulu.');
        }

        $filename = 'rekap-akun-guru-tu-' . Str::slug($batchCode) . '.csv';

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

    public function printSlips(?UserImportBatch $batch = null): View|RedirectResponse
    {
        if ($batch && $batch->exists) {
            $credentials = $batch->credentials;
            $batchLabel = $batch->batch_code . ' (' . $batch->created_at->format('d/m/Y H:i') . ')';
        } else {
            $sessionCreds = session('latest_import_credentials');
            if (!empty($sessionCreds)) {
                $credentials = $sessionCreds;
                $batchLabel = 'Batch Terbaru (' . now()->format('d/m/Y H:i') . ')';
            } else {
                $latestBatch = UserImportBatch::latest('id')->first();
                $credentials = $latestBatch?->credentials ?? $this->getLatestImportCredentials();
                $batchLabel = $latestBatch ? $latestBatch->batch_code . ' (' . $latestBatch->created_at->format('d/m/Y H:i') . ')' : 'Batch Terbaru';
            }
        }

        if (empty($credentials)) {
            return redirect()->route('admin.users.index')->with('error', 'Belum ada riwayat akun yang di-import. Silakan lakukan Import Guru & TU terlebih dahulu.');
        }

        $schoolName = Setting::get('school_name', config('app.name', 'Sistem Informasi Sekolah'));

        return view('admin.users.print-slips', compact('credentials', 'schoolName', 'batchLabel'));
    }

    public function destroyImportBatch(UserImportBatch $batch): RedirectResponse
    {
        $batchCode = $batch->batch_code;
        $batch->delete();

        // Sinkronkan cache setting dengan batch paling baru yang masih tersisa
        $latest = UserImportBatch::latest('id')->first();
        if ($latest) {
            $this->saveLatestImportCredentials($latest->credentials);
        } else {
            session()->forget('latest_import_credentials');
            Setting::where('key', 'latest_import_credentials')->delete();
            Setting::clearCache();
            if (Storage::disk('local')->exists('imports/latest_credentials.json')) {
                Storage::disk('local')->delete('imports/latest_credentials.json');
            }
        }

        return redirect()->route('admin.users.import.history')
            ->with('success', "Arsip import \"{$batchCode}\" berhasil dihapus dari riwayat.");
    }

    public function clearLatestImport(): RedirectResponse
    {
        session()->forget('latest_import_credentials');
        Setting::where('key', 'latest_import_credentials')->delete();
        Setting::clearCache();

        if (Storage::disk('local')->exists('imports/latest_credentials.json')) {
            Storage::disk('local')->delete('imports/latest_credentials.json');
        }

        return redirect()->route('admin.users.index')->with('success', 'Riwayat kredensial import telah dibersihkan.');
    }

    public function getLatestImportCredentials(): array
    {
        // 1. Cek session aktif
        $credentials = session('latest_import_credentials');
        if (! empty($credentials) && is_array($credentials)) {
            return $credentials;
        }

        // 2. Cek database settings (persisten antar admin dan tahan logout)
        $rawSetting = Setting::get('latest_import_credentials');
        if (! empty($rawSetting)) {
            $decoded = json_decode($rawSetting, true);
            if (is_array($decoded) && ! empty($decoded)) {
                return $decoded;
            }
        }

        // 3. Cek file storage privat lokal
        if (Storage::disk('local')->exists('imports/latest_credentials.json')) {
            $decoded = json_decode(Storage::disk('local')->get('imports/latest_credentials.json'), true);
            if (is_array($decoded) && ! empty($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    public function saveLatestImportCredentials(array $created): void
    {
        session(['latest_import_credentials' => $created]);

        Setting::updateOrCreate(
            ['key' => 'latest_import_credentials'],
            [
                'value' => json_encode($created),
                'type' => 'json',
                'group' => 'users',
                'label' => 'Riwayat Kredensial Import Guru & TU Terakhir',
            ]
        );
        Setting::clearCache();

        Storage::disk('local')->put('imports/latest_credentials.json', json_encode($created, JSON_PRETTY_PRINT));
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