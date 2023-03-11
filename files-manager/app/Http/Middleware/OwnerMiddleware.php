<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
class OwnerMiddleware
{
   
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->hasRole('owner')) {
            return $next($request);
        }
        else{
            return response()->json([
                'success' => false,
            ], 403);
        }
    }
}
