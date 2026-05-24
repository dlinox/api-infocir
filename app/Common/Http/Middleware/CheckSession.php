<?php

namespace App\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Common\Exceptions\ApiException;
use App\Modules\Auth\Repositories\SessionRepository;

class CheckSession
{
    public function __construct(
        private SessionRepository $sessionRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = (string) JWTAuth::getToken();

            if (!$this->sessionRepository->existsByToken($token)) {
                throw new ApiException('Sesión no válida o expirada', 401);
            }
        } catch (ApiException $e) {
            throw $e;
        } catch (\Exception) {
            throw new ApiException('Sesión no válida o expirada', 401);
        }

        return $next($request);
    }
}
