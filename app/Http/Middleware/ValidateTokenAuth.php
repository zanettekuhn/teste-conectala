<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $tokenServer = env('APP_TOKEN', '');
            $tokenClient = $request->bearerToken();

            if (!$tokenClient) {
                return response()->json([
                    'message' => 'Token Required',
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($tokenClient != $tokenServer) {
                return response()->json([
                    'message' => 'Token Unauthorized'
                ], Response::HTTP_UNAUTHORIZED);
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while validating the token',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
