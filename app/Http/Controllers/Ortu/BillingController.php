<?php
namespace App\Http\Controllers\Ortu;
use App\Http\Controllers\Controller;
use App\Models\BillingRecord;
use App\Models\User;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(User $student): View
    {
        abort_unless(
            auth()->user()->children()->where('users.id', $student->id)->exists(),
            403
        );

        $billings = BillingRecord::where('student_id', $student->id)
            ->orderByDesc('due_date')
            ->get();

        $totalUnpaid = $billings->where('status', 'unpaid')->sum('amount');
        $totalPaid   = $billings->where('status', 'paid')->sum('amount');

        return view('ortu.billing.index', compact('student', 'billings', 'totalUnpaid', 'totalPaid'));
    }
}
