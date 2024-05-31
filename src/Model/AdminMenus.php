<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Model;

class AdminMenus extends Model
{

    protected ?string $table = 'admin_menus';

    protected string $primaryKey = 'id';

    protected array $fillable = ['id', 'type', 'parent_id', 'title', 'slug', 'icon', 'uri', 'sort', 'created_at', 'updated_at'];

    protected array $casts = ['id' => 'integer', 'type' => 'integer', 'parent_id' => 'integer'];

    protected array $attributes = [
        'parent_id' => 0,
        'sort' => 1
    ];


    public static function getParentMenuIds($id)
    {
        $result = [$id];
        $parentId = $id;
        while ($parentId != 0) {
            $parent = self::where('id', $parentId)->first();
            $parentId = $parent['parent_id'];
            if ($parent && $parent['parent_id'] != 0) {
                $result[] = $parent['parent_id'];
            }
        }

        return $result;
    }

    public static function getMenuIdBySlug($slug){
        $menuId =  self::where('slug', $slug)->value('id');
        if($menuId){
            return $menuId;
        }
        return 0;
    }

}