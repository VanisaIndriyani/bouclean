<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'unread');
        $query = ContactMessage::query()->orderByDesc('created_at');

        if ($status === 'unread') {
            $query->where('is_read', false);
        }

        if ($request->filled('search')) {
            $search = (string) $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(10)->withQueryString();

        return view('contact-messages.index', compact('messages', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'pesan' => 'required|string|max:2000',
        ]);

        ContactMessage::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'pesan' => $validated['pesan'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim.');
    }

    public function markRead(ContactMessage $contactMessage)
    {
        $contactMessage->update(['is_read' => true]);

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }
}
