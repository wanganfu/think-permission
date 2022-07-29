<?php

namespace xiaodi\Permission\Traits;

use think\model\relation\BelongsToMany;
use xiaodi\Permission\Contract\RoleContract;

trait Permission
{
    /**
     * 获取权限所有的角色列表.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.role.model'),
            config('permission.user_role_access'),
            config('permission.role.foreign_key'),
            config('permission.permission.foreign_key')
        );
    }

    /**
     * 将当前权限分配到指定角色.
     *
     * @param RoleContract $role
     * @return void
     * @throws \think\db\exception\DbException
     */
    public function assignRole(RoleContract $role)
    {
        $this->roles()->attach($role);
    }

    /**
     * 移除已分配的角色.
     *
     * @param RoleContract $role
     * @return void
     */
    public function removeRole(RoleContract $role)
    {
        $this->roles()->detach($role);
    }

    /**
     * 通过名称查找规则.
     */
    public static function findByName($name)
    {
        return self::where(['name' => $name])->find();
    }
}
