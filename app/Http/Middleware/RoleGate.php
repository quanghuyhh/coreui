<?php

namespace App\Http\Middleware;

use App\Services\RoleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RoleGate
{
    public function __construct(protected RoleService $roleService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!auth()->check() && !in_array(Route::currentRouteName(), ['home', 'login'])) {
            return redirect()->route('login');
        }

        if (!$this->roleService->hasPermission($role)) {
            abort(403);
        }

        return $next($request);
    }
}
