<?php

namespace sistema\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;



class VerifyDummy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	
    public function handle($request, Closure $next,$dummy = null)
    {
    	if(Session::has('dummy') && (int)Session::get('dummy') == 1 ){
    		return redirect('auth/reset/reset');
    	}else{
    		return $next($request);
    	}
        return $next($request);
    }
}
