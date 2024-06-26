<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if($request->hasHeader('Eshikhon-Token') && $request->header('Eshikhon-Token') == "321456"){
        return $next($request);
    }else{
        return response()->json ([
            "result"=>false,
            "message"=>"access denied"
        ]);
    }

    }
}
