<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Liuxinyang\HyperfAdmin\Controller\Admin;

use Liuxinyang\HyperfAdmin\Model\AdminMenus;
use Liuxinyang\HyperfAdmin\Model\AdminRolePermissions;
use Liuxinyang\HyperfAdmin\Model\AdminRoleUsers;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Stringable\Str;
use Hyperf\View\RenderInterface;
use Psr\Container\ContainerInterface;
use Yangliuxin\Utils\Utils\TreeUtils;

abstract class AbstractAdminController
{
    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected ResponseInterface $response;

    #[Inject]
    protected SessionInterface $session;

    #[Inject]
    protected RenderInterface $render;

    protected array $bladeData;

    protected function _init()
    {
        $user = $this->session->get("admin");
        $cookie = $this->request->cookie('name');
        if (!$user && !$cookie) {
            return false;
        }
        if($user){
            $user = json_decode($user,true);
        } else {
            $user = json_decode($cookie, true);
        }
        if(!$user){
            return false;
        }

        $this->bladeData['user'] = $user;
        $menuDataList = AdminMenus::where('type', 1)->get()->toArray();
        $roleId = AdminRoleUsers::where('user_id', $user['id'])->value('role_id');
        $permissions = AdminRolePermissions::getAllPermissions($roleId);
        foreach ($menuDataList as $key => $menu) {
            if ($menu['uri'] == '') {
                $menuDataList[$key]['uri'] = '/admin/#';
            } elseif ($menu['uri'] == '/') {
                $menuDataList[$key]['uri'] = '/admin';
            } else {
                $menuDataList[$key]['uri'] = '/admin/' . $menu['uri'];
            }
            if (!self::hasPermission($menu['id'], $permissions) && $user['username'] != 'Admin' && $roleId != 1) {
                unset($menuDataList[$key]);
            }
        }

        $this->bladeData['menuDataList'] = TreeUtils::getTree($menuDataList);
        if(!$this->session->get('csrf_token')){
            $this->session->set('csrf_token', str_random(32));
        }
        $this->bladeData['_token'] = $this->session->get('csrf_token');
        return $user;
    }

    protected function dealFiletype($file)
    {
        if ($file) {
            $image = file_get_contents($file->getRealPath());
            if ($image) {
                $imagePath = '/uploads/images/' . Str::random(10) . '.png';
                $storagePath = realpath(BASE_PATH . '/public');
                file_put_contents($storagePath . $imagePath, $image);
                return $imagePath;
            }
            return null;
        }
        return null;
    }

    protected static function hasPermission($menuId, $permissions): bool
    {
        if (in_array($menuId, $permissions)) {
            return true;
        }
        return false;

    }

}
