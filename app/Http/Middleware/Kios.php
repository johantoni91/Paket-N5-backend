<?php

namespace App\Http\Middleware;

use App\Models\TcHardware;
use Illuminate\Support\Facades\Request;
use Closure;
use Jenssegers\Agent\Agent;

class Kios
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        $perangkat = TcHardware::where('serial_number', $token)->first();
        if ($perangkat) {
            return $next($request);
        }
        $agent = new Agent();
        return response()->json([
            'status'    => false,
            'message'   => 'Unauthorized',
            'trespasser'      => [
                'ip_address' => Request::ip(),
                'device'     => $agent->device(),
                'os'         => $agent->platform(),
            ]
        ], 401);

        return $next($request);
    }
}
