<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Backup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index(): View
    {
        $backups = Backup::with('creator')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.backups.index', compact('backups'));
    }

    public function create(Request $request): RedirectResponse
    {
        $type = $request->input('type', 'database');
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$type}_{$timestamp}.sql";

        try {
            $tables = Schema::getTableListing();
            $sqlContent = "-- Web Sekolah Modern Database Backup\n";
            $sqlContent .= "-- Generated at: " . now()->toDateTimeString() . "\n\n";
            $sqlContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $rows = DB::table($table)->get();
                $sqlContent .= "-- Table: `{$table}` --\n";

                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $keys = array_keys($rowArray);
                    $escapedKeys = array_map(fn ($k) => "`{$k}`", $keys);
                    $values = array_values($rowArray);
                    $escapedValues = array_map(function ($v) {
                        if ($v === null) return 'NULL';
                        return "'" . addslashes((string) $v) . "'";
                    }, $values);

                    $sqlContent .= "INSERT INTO `{$table}` (" . implode(', ', $escapedKeys) . ") VALUES (" . implode(', ', $escapedValues) . ");\n";
                }
                $sqlContent .= "\n";
            }
            $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;\n";

            $path = "backups/{$filename}";
            Storage::disk('local')->put($path, $sqlContent);
            $fileSize = Storage::disk('local')->size($path);

            $backup = Backup::create([
                'filename'   => $filename,
                'disk'       => 'local',
                'file_path'  => $path,
                'file_size'  => $fileSize,
                'type'       => $type,
                'status'     => 'success',
                'created_by' => auth()->id(),
            ]);

            AuditLog::log('create', "Membuat backup database manual: {$filename}", $backup);

            return back()->with('success', "Backup database `{$filename}` berhasil dibuat.");
        } catch (\Throwable $e) {
            return back()->with('error', "Gagal membuat backup: " . $e->getMessage());
        }
    }

    public function download(Backup $backup): StreamedResponse
    {
        AuditLog::log('download', "Mengunduh file backup: {$backup->filename}", $backup);

        return Storage::disk($backup->disk)->download($backup->file_path, $backup->filename);
    }

    public function destroy(Backup $backup): RedirectResponse
    {
        $filename = $backup->filename;
        Storage::disk($backup->disk)->delete($backup->file_path);
        $backup->delete();

        AuditLog::log('delete', "Menghapus file backup: {$filename}");

        return back()->with('success', "File backup `{$filename}` berhasil dihapus.");
    }
}