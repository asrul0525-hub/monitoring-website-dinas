<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::with('dinas')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unreadCount = Notifikasi::where('is_read', false)->count();

        $data = $notifikasis->map(function ($notif) {
            return [
                'id' => $notif->id,
                'type' => $notif->type,
                'title' => $notif->title,
                'message' => $notif->message,
                'dinas_nama' => $notif->dinas->nama_dinas ?? 'Sistem',
                'time' => $notif->created_at->diffForHumans(),
                'is_read' => $notif->is_read,
            ];
        });

        return response()->json([
            'unread_count' => $unreadCount,
            'data' => $data,
        ]);
    }

    public function markAsRead()
    {
        Notifikasi::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['status' => 'success']);
    }
}