<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class CsrfMiddleware
{
    public function handle(Request $request): ?Response
    {
        // Only check CSRF for POST, PUT, DELETE requests
        if (!in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            return null;
        }

        // Skip CSRF check for API routes (should use other auth methods)
        if (strpos($request->path(), '/api/') === 0) {
            return null;
        }

        // Validate CSRF token
        if (!$request->validateCsrf()) {
            return $this->invalidToken($request);
        }

        // Allow request to continue
        return null;
    }

    private function invalidToken(Request $request): Response
    {
        // If AJAX request, return JSON
        if ($request->isAjax()) {
            return Response::make()
                ->json(['error' => 'CSRF token mismatch'], 419);
        }

        // Redirect back with error
        $response = Response::make();
        $response->withErrors(['csrf' => 'CSRF token mismatch. Please try again.']);
        $response->back();
        
        return $response;
    }
}