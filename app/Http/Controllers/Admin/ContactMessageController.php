<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $search = $request->string('search')->trim()->toString();

        $query = ContactMessage::query()
            ->when($status === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($status === 'read', fn ($q) => $q->where('is_read', true))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            });

        $messages = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $counts = [
            'all'    => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'read'   => ContactMessage::where('is_read', true)->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'status', 'search', 'counts'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        if (! $contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function toggleRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['is_read' => ! $contactMessage->is_read]);

        $statusText = $contactMessage->is_read ? 'ditandai sudah dibaca' : 'ditandai belum dibaca';

        return back()->with('success', "Pesan berhasil {$statusText}.");
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
