<?php
namespace App\Http\Controllers\Ortu;
use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(): View
    {
        $children = auth()->user()->children()->get();
        $requests = LeaveRequest::with(['student'])
            ->where('parent_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('ortu.leave-requests.index', compact('requests', 'children'));
    }

    public function create(): View
    {
        $children = auth()->user()->children()->get();
        abort_if($children->isEmpty(), 403, 'Tidak ada anak yang tertaut ke akun Anda.');
        return view('ortu.leave-requests.create', compact('children'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'  => ['required', 'integer', 'exists:users,id'],
            'type'        => ['required', 'in:sakit,izin'],
            'start_date'  => ['required', 'date', 'after_or_equal:today'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'reason'      => ['required', 'string', 'max:2000'],
            'attachment'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        // Pastikan siswa adalah anak dari ortu yang login
        abort_unless(
            auth()->user()->children()->where('users.id', $validated['student_id'])->exists(),
            403
        );

        $data = [
            'parent_id'  => auth()->id(),
            'student_id' => $validated['student_id'],
            'type'       => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
            'reason'     => $validated['reason'],
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $data['attachment_path'] = $file->store('izin-siswa', 'public');
            $data['attachment_original_name'] = $file->getClientOriginalName();
        }

        LeaveRequest::create($data);

        return redirect()->route('ortu.leave-requests.index')
            ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan guru.');
    }
}
