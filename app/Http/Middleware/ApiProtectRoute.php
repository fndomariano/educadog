<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\{TokenExpiredException, TokenInvalidException};


class ApiProtectRoute extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {

            JWTAuth::parseToken()->authenticate();

        } catch (\Exception $exception) {

            if ($exception instanceof TokenInvalidException) {
                return response()->json(['status' => 'O token é inválido']);
            } 
            
            if ($exception instanceof TokenExpiredException) {
                return response()->json(['status' => 'O token expirou']);
            } 
            
            return response()->json(['status' => 'O token não foi encontrado']);
        }
        
        return $next($request);
    }
}
