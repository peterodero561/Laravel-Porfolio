<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CachePublicPage
{
    /**
     * route name => TTL in seconds
     */
    private const CACHEABLE = [
        'home' => 300,
        'about' => 600,
        'services' => 600,
        'resume' => 300,
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.env') === 'testing' && env('PORTFOLIO_DISABLE_RESPONSE_CACHE', true)) {
            return $next($request);
        }

        if (! $request->isMethod('GET') || $request->user()) {
            return $next($request);
        }

        $route = $request->route()?->getName();

        if (! $route || ! isset(self::CACHEABLE[$route])) {
            return $next($request);
        }

        $key = $this->keyFor($request, $route);

        $cached = Cache::get($key);

        if ($cached !== null) {
            return response($cached['body'], $cached['status'])
                ->withHeaders(array_merge($cached['headers'], [
                    'X-Page-Cache' => 'HIT',
                ]));
        }

        $response = $next($request);

        // Only cache successful HTML responses.
        if ($response->getStatusCode() !== 200
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        Cache::put($key, [
            'body' => $response->getContent(),
            'status' => $response->getStatusCode(),
            'headers' => array_filter([
                'Content-Type' => $response->headers->get('Content-Type'),
            ]),
        ], now()->addSeconds(self::CACHEABLE[$route]));

        $response->headers->set('X-Page-Cache', 'MISS');

        return $response;
    }

    private function keyFor(Request $request, string $route): string
    {
        return 'page:'.$route.':'.sha1($request->fullUrl());
    }
}
