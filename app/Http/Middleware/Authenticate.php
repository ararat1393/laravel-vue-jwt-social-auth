<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Concerns\InteractsWithInput;
use Illuminate\Http\Response;


class Authenticate extends Middleware
{
    /**
     * Override handle method
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->authenticate($request, $guards) === 'authentication_failed') {
            return response()->json(['error'=>'Unauthorized'],Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }

    /**
     * Override authentication method
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @return string|void
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }
        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }
        return 'authentication_failed';
    }

}
