<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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

        // $domain = ['http://localhost:8080'];

        // if (isset($request->server()['HTTP_ORIGIN'])) {
        //     $origin = $request->server()['HTTP_ORIGIN'];
        //     if (in_array($origin, $domain)) {
        //         header('Access-Control-Allow-Origin: ' . $origin);
        //         header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Auth-Token, Origin');
        //     }
        // }


        // $response = $next($request);
        // $response->headers->set('Access-Control-Allow-Origin', '*');
        // $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        // $response->headers->set('Access-Control-Allow-Headers', 'Content-type, Authorization, X-Requested-With, Application');

        // header("Access-Control-Allow-Origin: *");

        // $headers = [
        //     'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE',
        //     'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, X-Auth-Token, Origin',
        // ];

        // if($request->getMethod()== "OPTIONS"){
        //     return response()->json("OK", 200, $headers);
        // }
        // $response = $next($request);
        // foreach($headers as $key => $value){
        //     $response->header($key, $value);
        // }
        // return $response;


        return $next($request)
            ->header('Access-Control-Allow-Origin', 'http://localhost:8080')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', '*')
            ->header('Access-Control-Request-Headers', '*')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization');
    }
}
