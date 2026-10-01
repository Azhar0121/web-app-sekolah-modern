<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['unread_count' => 0, 'notifications' => []]);
        }

        $unreadCount  = $user->unreadNotifications->count();
        $notifications = $user->notifications()->take(10)->get()->map(fn ($n) => [
            'id'         => $n->id,
            'title'      => $n->data['title'] ?? 'Notifikasi Sistem',
            'message'    => $n->data['message'] ?? '',
            'url'        => $n->data['url'] ?? '#',
            'icon'       => $n->data['icon'] ?? 'bell',
            'read_at'    => $n->read_at?->diffForHumans(),
            'created_at' => $n->created_at->diffForHumans(),
        ]);

        return response()->json([
            'unread_count'  => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $id): JsonResponse
    {
        $user = auth()->user();
        if ($user) {
            $notification = $user->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function markAllAsRead(): RedirectResponse
    {
        $user = auth()->user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
