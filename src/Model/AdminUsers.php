<?php
declare(strict_types=1);

namespace App\Model;

class AdminUsers extends Model
{

    protected ?string $table = 'admin_users';

    protected string $primaryKey = 'id';

    protected array $fillable = ['id', 'username', 'password', 'salt', 'avatar', 'created_at', 'updated_at'];

    protected array $casts = ['id' => 'integer'];

}