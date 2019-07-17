<?php

namespace App\Http\Middleware;

use Closure;use Illuminate\Support\Facades\Redis;

class BrushMiddleware
{
    protected $limit="20";
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*$key=$request->ip();
        $expirekey=$key."expire";
        if(Redis::exists($expirekey)){
            die(json_encode(Redis::get($expirekey),JSON_UNESCAPED_UNICODE));
        }
        if(Redis::exists($key)){
            Redis::incr($key);
            Redis::expire($key,60);
            $count=Redis::get($key);
            if($count>$this->limit){
                Redis::set($expirekey,"接口上线，请三分钟后尝试");
                Redis::expire($expirekey,180);
                die(json_encode(Redis::get($expirekey),JSON_UNESCAPED_UNICODE));
            }else{
                return $next($request);
            }
        }else{
            Redis::incr($key);
            Redis::expire($key,60);
            return $next($request);
        }*/

        return $next($request);


    }
}
