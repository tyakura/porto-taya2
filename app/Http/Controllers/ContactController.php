<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    // POST /api/contact — simpan pesan kontak dari pengunjung (ERD: CONTACT_MESSAGE)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name'  => 'required|string|max:100',
            'sender_email' => 'required|email|max:150',
            'message'      => 'required|string|max:2000',
            'session_id'   => 'nullable|uuid',
        ]);

        // Verifikasi session_id jika dikirim
        $sessionId = null;
        if (! empty($validated['session_id'])) {
            $exists = DB::table('chat_sessions')->where('id', $validated['session_id'])->exists();
            $sessionId = $exists ? $validated['session_id'] : null;
        }

        try {
            DB::table('contact_messages')->insert([
                'session_id'   => $sessionId,
                'sender_name'  => $validated['sender_name'],
                'sender_email' => $validated['sender_email'],
                'message'      => $validated['message'],
                'status'       => 'new',
                'created_at'   => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to save contact message', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Gagal mengirim pesan. Coba lagi atau hubungi langsung via email.',
            ], 500);
        }

        return response()->json([
            'ok'      => true,
            'message' => 'Pesan berhasil dikirim! Taya akan membalasnya Senin–Jumat, 09.00–17.00 WIB.',
        ], 201);
    }
}
