<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $currentMode = $user->mode;
        $sudo = $user->hasRole('Administrator');

        $path = $request->path();

        $newMode = '';

        if (str_contains($path, 'aset') || str_contains($path, 'kdp') || str_contains($path, 'laporan/bmn')) {
            $newMode = 'aset';
        } elseif (str_contains($path, 'persediaan')) {
            $newMode = 'persediaan';
        }

        if (($newMode) && ($newMode !== $currentMode) && ($sudo || $user->roles->count() > 1)) {
            $user->mode = $newMode;

            if ($newMode == 'aset') {
               //JIKA MODE BARU ADALAH ASET MAKA ACTIVE ROLE AKAN DIARAHKAN KE ROLE ASET YANG DIMILIKI OLEH USER
                $role = $user->roles()
                             ->where('name', 'LIKE', '%Aset%')
                             ->orWhere('name', 'LIKE', '%KDP%')
                             ->first();
            } elseif ($newMode == 'persediaan') {
                //JIKA MODE BARU ADALAH PERSEDIAAN MAKA ACTIVE ROLE AKAN DIARAHKAN KE ROLE PERSEDIAAN YANG DIMILIKI OLEH USER
                $role = $user->roles()
                             ->where('name', 'LIKE', '%Persediaan%')
                             ->first();
            }

            if (isset($role)) {
                $user->active_role_id = $role->id;
            }
            $user->save();

            session()->flash('mode-switched', 'Mode telah diganti ke ' . $newMode);
        }

        return $next($request);
    }
}
