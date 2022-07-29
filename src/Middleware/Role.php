<?php

namespace xiaodi\Permission\Middleware;

use think\Request;
use think\Response;
use xiaodi\Permission\Contract\RoleMiddlewareContract;
use xiaodi\Permission\Contract\UserContract;

/**
 * 角色权限中间件.
 */
class Role implements RoleMiddlewareContract
{
    public function handle($request, \Closure $next, $role)
    {
        if (!$request->user) {
            return $this->handleNotLoggedIn($request);
        }

        if (false === $this->requestHasRole($request, $request->user, $role)) {
            return $this->handleNoAuthority($request);
        }

        return $next($request);
    }

    /**
     * 检查是否有权限.
     *
     * @param Request $request
     * @param UserContract $user
     * @param string $role
     * @return bool
     */
    public function requestHasRole(Request $request, UserContract $user, string $role): bool
    {
        if (!$user->hasRole($role)) {
            return false;
        }

        return true;
    }

    /**
     * 用户未登录.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handleNotLoggedIn(Request $request): Response
    {
        return Response::create(['message' => '用户未登录', 'code' => '50000'], 'json', 401);
    }

    /**
     * 没有权限.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handleNoAuthority(Request $request): Response
    {
        return Response::create(['message' => '没有权限', 'code' => '50001'], 'json', 401);
    }
}
