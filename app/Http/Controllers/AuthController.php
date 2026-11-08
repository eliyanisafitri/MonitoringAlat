<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Menampilkan form login
    public function index()
    {
        if (Session::has('is_login')) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'required'
        ]);

        // Cek database
        $user = DB::table('users')->where('nama', $request->nama)->first();

        // Jika user ada dan password (PIN) cocok
        if ($user && Hash::check($request->password, $user->password)) {
            Session::put([
                'user_id' => $user->id,
                'nama' => $user->nama,
                'is_login' => true
            ]);

            return redirect('/dashboard');
        }

        return back()->with('error', 'PIN salah! Silakan coba lagi.');
    }

    // Memproses logout
    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}