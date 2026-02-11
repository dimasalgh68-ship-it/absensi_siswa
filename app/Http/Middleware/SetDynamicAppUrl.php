<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetDynamicAppUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Detect if running behind ngrok or proxy
        if ($request->header('X-Forwarded-Proto') === 'https' || 
            $request->header('X-Forwarded-Host') ||
            str_contains($request->header('Host', ''), 'ngrok') ||
            str_contains($request->header('Host', ''), '.app')) {
            
            // Force HTTPS for assets
            URL::forceScheme('https');
            
            // Set the root URL dynamically
            $host = $request->header('X-Forwarded-Host') ?: $request->header('Host');
            if ($host) {
                $appUrl = 'https://' . $host;
                URL::forceRootUrl($appUrl);
                config(['app.url' => $appUrl]);
                
                // Update filesystem disk URL for Storage::url() to work with ngrok
                config(['filesystems.disks.public.url' => $appUrl . '/storage']);
            }
        }

        return $next($request);
    }
}
