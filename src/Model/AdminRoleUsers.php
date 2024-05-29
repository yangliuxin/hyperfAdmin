<?php
declare(strict_types=1);

namespace App\Model;

class AdminRoleUsers extends Model
{

    protected ?string $table = 'admin_role_users';

    protected string $primaryKey = 'id';

    protected array $fillable = ['id', 'role_id', 'user_id', 'created_at', 'updated_at'];

    protected array $casts = ['id' => 'integer', 'role_id' => 'integer', 'user_id' => 'integer'];

    public static function getRoleIdByUserId($userId): int{
        $roleId = AdminRoleUsers::where('user_id', $userId)->value('role_id');
        if(!$roleId){
            return 0;
        }
        return $roleId;
    }

}