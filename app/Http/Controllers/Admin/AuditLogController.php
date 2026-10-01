<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $eventFilter = $request->query('event');
        $search      = $request->string('search')->trim()->toString();

        $logs = AuditLog::with('user')
            ->when($eventFilter, fn ($q) => $q->where('event', $eventFilter))
            ->when($search, fn ($q) => $q->where(function ($sub) use ($search) {
                $sub->where('user_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.audit-logs.index', compact('logs', 'eventFilter', 'search'));
    }
}
