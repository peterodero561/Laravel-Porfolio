<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestQueries
{
    /** Per-route query budgets — tune these as the site grows. */
    private const BUDGETS = [
        'home' => 4,
        'about' => 4,
        'services' => 4,
        'projects.index' => 3,
        'projects.show' => 3,
        'resume' => 2,
        'contact' => 2,
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->isLocal()) {
            return $next($request);
        }

        $queries = [];

        DB::listen(function ($query) use (&$queries) {
            $queries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ];
        });

        $response = $next($request);

        $routeName = $request->route()?->getName() ?? '(unnamed)';
        $total = count($queries);
        $budget = self::BUDGETS[$routeName] ?? null;

        $summary = "{$request->method()} {$request->path()} → {$total} queries in {$response->getStatusCode()}";

        if ($budget !== null && $total > $budget) {
            Log::warning("[N+1?] {$summary} (budget: {$budget})", [
                'sql' => array_map(fn ($q) => $q['sql'], $queries),
            ]);
        } else {
            Log::info($summary);
        }

        return $response;
    }
}
