<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RateLimitMiddleware
{
    public function __construct(
        private readonly RateLimiter $limiter
    ) {}

    public function handle(Request $request, Closure $next, int $maxAttempts = 60)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'code' => 'error',
                'message' => 'Too many requests. Please try again later.'
            ], 429);
        }

        $this->limiter->hit($key, 60);

        $response = $next($request);

        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $maxAttempts - $this->limiter->attempts($key)),
        ]);

        return $response;
    }

    protected function resolveRequestSignature(Request $request): string
    {
        // Redis: could use more sophisticated rate limiting per token
        return sha1($request->ip() . '|' . $request->userAgent());
    }
}