<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Model;

class AdminStats extends Model
{

    protected ?string $table = 'admin_stats';

    protected string $primaryKey = 'id';

    protected array $fillable = ['id',  'uri', 'ip', 'province', 'city', 'created_at', 'updated_at'];

    protected array $casts = ['id' => 'integer'];

}