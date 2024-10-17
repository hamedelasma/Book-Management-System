<?php

namespace App\Http\Middleware;

use App\Enum\UserRoles;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role !== UserRoles::ADMIN) {
            throw new AuthorizationException('You are not authorized to perform this action');
        }
        return $next($request);
    }
}
