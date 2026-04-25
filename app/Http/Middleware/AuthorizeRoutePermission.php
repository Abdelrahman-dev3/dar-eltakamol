<?php

namespace App\Http\Middleware;

use App\Support\RoutePermissionMap;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeRoutePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $requiredPermissions = RoutePermissionMap::permissionsForRoute($request->route()?->getName());

        if (empty($requiredPermissions)) {
            return $next($request);
        }

        $user = $request->user();

        if ($user && $user->hasAnyPermission($requiredPermissions)) {
            return $next($request);
        }

        abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
    }
}
