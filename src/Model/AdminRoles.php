<?php
declare(strict_types=1);

namespace App\Model;

class AdminRoles extends Model
{

    protected ?string $table = 'admin_roles';

    protected string $primaryKey = 'id';

    protected array $fillable = ['id', 'name', 'slug', 'created_at', 'updated_at'];

    protected array $casts = ['id' => 'integer'];

}