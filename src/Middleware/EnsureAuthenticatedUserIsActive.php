<?php

namespace Thefeqy\ModelStatus\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Thefeqy\ModelStatus\Status;

class EnsureAuthenticatedUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fetch the status column name from the configuration
        $columnName = Config::get('model-status.column_name', 'status');

        // Check the user's status
        if ($request->user()->{$columnName} !== Status::active()) {
            auth()->logout();

            throw new AccessDeniedHttpException(
                'This account is suspended. Please contact the administrator.'
            );
        }

        return $next($request);
    }
}
