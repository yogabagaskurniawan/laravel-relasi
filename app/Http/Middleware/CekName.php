<?php

namespace App\Http\Middleware;

use Closure;

class CekName
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$names)
    {
        // jika name ditabel user sama dengan name yang $names
        if(in_array($request->user()->name,$names)){
            return $next($request);
        }
        // jika tidak ada 
        return redirect('/products');
    }
}
