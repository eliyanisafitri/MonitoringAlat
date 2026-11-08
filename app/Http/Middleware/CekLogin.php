<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekLogin
{
	public function handle(Request $request, Closure $next)
	{
		// Belum login: kembalikan ke halaman login
		if (!session('is_login')) {
			return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
		}

		$response = $next($request);

		// Cegah halaman lama tampil lagi lewat tombol Back setelah logout
		return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
			->header('Pragma', 'no-cache');
	}
}