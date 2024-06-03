<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CacheApi{
    public static function cacheApiResponse($key, $callback, $expiration = null) {
        if (Cache::has($key)) {
            $cachedData = Cache::get($key);
            $expirationTime = Cache::get($key . '_expires_at');
    
            if ($expirationTime && $expirationTime > now()) {
                return $cachedData; 
            } else {
 
                Cache::forget($key);
                Cache::forget($key . '_expires_at');
            }
        }
    
        $response = $callback();
    
        $expirationTime = Carbon::now()->addMinutes($expiration ?: 1);
        Cache::put($key, $response, $expirationTime);
        Cache::put($key . '_expires_at', $expirationTime);
    
        return $response;
    }

}
?>