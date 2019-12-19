<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class JsonRequestMiddleware
{

    private $method = ['POST', 'PUT', 'PATCH'];

    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->method(), $this->method)
            && $request->isJson()
        ) {
            $data = $request->json()->all();
            $request->request->replace(is_array($data) ? $data : []);
        }
        return $next($request);
    }

}