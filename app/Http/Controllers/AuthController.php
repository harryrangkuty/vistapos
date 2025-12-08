<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Configuration;
use App\Models\User;

class AuthController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');

        // $this->middleware(function ($request, $next) {

        //     $tahunAktif = Configuration::where('key', 'TAHUN-PEROLEHAN-AKTIF')->value('value') ?? date('Y');

        //     $daftarTahun = explode(',', $tahunAktif);

        //     $user = auth()->user();

        //     if ($user && $user->tahun_kerja && !in_array($user->tahun_kerja, $daftarTahun)) {
        //         $user->tahun_kerja = null;
        //         $user->save();
        //         session(['reset-tahun-kerja' => true]);
        //     }

        //     return $next($request);
        // });
    }

    public function authenticate(Request $request)
    {
        // Validasi input
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');

        // Cari user berdasarkan identifier
        $user = User::where('identifier', $identifier)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 401);
        }

        // Verifikasi password
        if (!Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Kata sandi salah.'], 401);
        }

        // Login user
        Auth::login($user, true);

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function user()
    {
        return request()->user();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
