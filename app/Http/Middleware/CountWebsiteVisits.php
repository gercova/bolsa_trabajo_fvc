<?php

namespace App\Http\Middleware;

use App\Models\VisitorCounter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountWebsiteVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only count GET requests and public web pages (ignore AJAX, API, admin routes, static/debug routes)
        if ($this->shouldCountVisit($request)) {
            try {
                VisitorCounter::incrementViews('total_website_visits');
            } catch (\Throwable $e) {
                // Silently ignore to avoid breaking the user experience if DB is unavailable
                report($e);
            }
        }

        return $next($request);
    }

    /**
     * Determine if the request should be counted as a website view.
     */
    protected function shouldCountVisit(Request $request): bool
    {
        if (!$request->isMethod('GET')) {
            return false;
        }

        if ($request->ajax() || $request->pjax() || $request->wantsJson()) {
            return false;
        }

        // Ignore admin panel, backend auth actions, and utility/asset routes
        if ($request->is(
            'admin*',
            'admin-dashboard*',
            'admin-perfil*',
            'admin-blogs*',
            'admin-exams*',
            'admin-scholarships*',
            'admin-tupa*',
            'admin-documentos*',
            'admin-areas*',
            'admin-programas*',
            'admin-trabajos*',
            'admin-usuarios*',
            'admin-docentes-roles*',
            'admin-consejo-estudiantil*',
            'admin-enlaces*',
            'admin-roles*',
            'admin-reclamos*',
            'admin-socios*',
            'admin-empresa*',
            'livewire*',
            'telescope*',
            '_debugbar*',
            'sanctum*',
            'api*',
            'up',
            'storage*'
        )) {
            return false;
        }

        return true;
    }
}
