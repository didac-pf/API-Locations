<?php
/**
 * Created by PhpStorm.
 * User: didac
 * Date: 11/10/16
 * Time: 12:27
 */

namespace App\Http\Middleware;

use Closure;

class Access
{

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if($request->header('api_token') === env('API_TOKEN')) {
            return $next($request);
        } else {
            if($request->server('PATH_INFO') === '/unauthorized') {
                return $next($request);
            } else {
                return redirect('/unauthorized');
            }
        }
    }

}