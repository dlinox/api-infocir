<?php

namespace App\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\Behavior\BehaviorProfile;
use App\Common\Exceptions\PermissionDeniedException;

class CheckPermission
{
    /**
     * Handle permission check for the request.
     *
     * Usage:
     *   ->middleware('permission:view_users')
     *   ->middleware('permission:create_users,manage_users') // OR logic
     *
     * @param string $permissions Comma-separated permission names (OR logic)
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $requiredPermissions = $permissions;

        // Get user permissions from cache or load them
        $userPermissions = $this->getUserPermissions($request);

        // If user is super admin, bypass permission check
        if ($request->attributes->get('is_super_admin', false)) {
            return $next($request);
        }

        // Check if user has at least one of the required permissions (OR)
        foreach ($requiredPermissions as $permission) {
            if (in_array($permission, $userPermissions, true)) {
                return $next($request);
            }
        }

        throw new PermissionDeniedException($requiredPermissions[0]);
    }

    /**
     * Get user permissions from request cache or load from database.
     * Caches permissions and super admin status in request attributes for performance.
     *
     * @return array<string>
     */
    private function getUserPermissions(Request $request): array
    {
        // Check if already cached in this request
        if ($request->attributes->has('user_permissions')) {
            return $request->attributes->get('user_permissions');
        }

        $permissions = [];
        $isSuperAdmin = false;

        // Get profile ID from JWT 'prf' claim
        $profileId = $this->getProfileIdFromToken();

        if ($profileId) {
            // Load profile with role and permissions (single optimized query)
            $profile = BehaviorProfile::with('role.permissions')
                ->where('id', $profileId)
                ->where('is_active', true)
                ->first();

            if ($profile?->role) {
                // Check if user is super admin
                if ($profile->role->name === 'super_admin' && (int)$profile->role->level === 0) {
                    $isSuperAdmin = true;
                }

                $permissions = $profile->role->permissions
                    ->pluck('name')
                    ->toArray();
            }
        }

        // Cache in request attributes
        $request->attributes->set('is_super_admin', $isSuperAdmin);
        $request->attributes->set('user_permissions', $permissions);

        return $permissions;
    }

    /**
     * Extract profile ID from JWT claims.
     */
    private function getProfileIdFromToken(): ?int
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            return $payload->get('prf');
        } catch (\Exception) {
            return null;
        }
    }
}
