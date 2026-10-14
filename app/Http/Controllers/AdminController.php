<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private const ADMIN_EMAIL    = 'attayaarkarna12@gmail.com';
    private const ADMIN_PASSWORD = 'admin123';
    private const SESSION_KEY    = 'admin_logged_in';

    // ── GET /admin/login ────────────────────────────────────────────────────
    public function showLogin()
    {
        if (session(self::SESSION_KEY)) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // ── POST /admin/login ───────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (
            $request->email    === self::ADMIN_EMAIL &&
            $request->password === self::ADMIN_PASSWORD
        ) {
            $request->session()->regenerate();
            session([self::SESSION_KEY => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    // ── POST /admin/logout ──────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);
        return redirect()->route('admin.login');
    }

    // ── GET /admin/dashboard ────────────────────────────────────────────────
    public function dashboard()
    {
        // ── Visitor stats ───────────────────────────────────────────────────
        $totalSessions  = DB::table('chat_sessions')->count();
        $todaySessions  = DB::table('chat_sessions')
            ->whereDate('started_at', today())->count();
        $weekSessions   = DB::table('chat_sessions')
            ->where('started_at', '>=', now()->subDays(7))->count();

        // Sesi per hari (7 hari terakhir) untuk mini-chart
        $dailyStats = DB::table('chat_sessions')
            ->selectRaw('DATE(started_at) as date, COUNT(*) as count')
            ->where('started_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Device breakdown
        $deviceStats = DB::table('chat_sessions')
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->get();

        // Total pesan chat
        $totalMessages = DB::table('chat_messages')->count();

        // ── Contact messages ────────────────────────────────────────────────
        $contactMessages = DB::table('contact_messages')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $totalContacts = DB::table('contact_messages')->count();
        $newContacts   = DB::table('contact_messages')
            ->where('status', 'new')->count();

        return view('admin.dashboard', compact(
            'totalSessions',
            'todaySessions',
            'weekSessions',
            'dailyStats',
            'deviceStats',
            'totalMessages',
            'contactMessages',
            'totalContacts',
            'newContacts'
        ));
    }
}
