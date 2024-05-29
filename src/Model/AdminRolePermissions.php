<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Model;

class AdminRolePermissions extends Model
{

    protected ?string $table = 'admin_role_permissions';

    protected string $primaryKey = 'id';

    protected array $fillable = ['id', 'role_id', 'permission_id', 'created_at', 'updated_at'];

    protected array $casts = ['id' => 'integer', 'role_id' => 'integer', 'permission_id' => 'integer'];


    public static function getAllPermissions($roleId): array
    {
        $result = [];
        $permissions = self::where('role_id', $roleId)->pluck("permission_id")->toArray();
        foreach ($permissions as $key => $permission) {
            $result = array_merge($result, AdminMenus::getParentMenuIds($permission));

        }
        return array_unique($result);
    }

}