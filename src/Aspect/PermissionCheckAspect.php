<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Aspect;

use App\Service\Log;
use Hyperf\Context\ApplicationContext;
use Hyperf\Context\Context;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Liuxinyang\HyperfAdmin\Annotaion\PermissionCheck;
use Hyperf\Di\Annotation\Aspect;
use Liuxinyang\HyperfAdmin\Model\AdminMenus;
use Liuxinyang\HyperfAdmin\Model\AdminRolePermissions;
use Liuxinyang\HyperfAdmin\Model\AdminRoleUsers;
use Psr\Http\Message\ResponseInterface;

#[Aspect(
    annotations: [
        PermissionCheck::class
    ]
)]
class PermissionCheckAspect extends AbstractAspect
{

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $annotation = $proceedingJoinPoint->getAnnotationMetadata()->method[PermissionCheck::class] ?? null;
        $slug = $annotation->slug;
        Log::info("METADATA:".$slug, [$annotation]);
        $response = Context::get(ResponseInterface::class);
        $session =  Context::get(SessionInterface::class);
        $render = ApplicationContext::getContainer()->get(RenderInterface::class);
        $user = json_decode($session->get("admin"), true);
        if(!$this->checkPermission($user['id'], $slug)){
            throw new \Exception('no permission', 403);
        } else {
            return $proceedingJoinPoint->processOriginalMethod();
        }

    }

    protected static function hasPermission($uid, $targetId): bool
    {
        if ($uid == 1) {
            return true;
        }
        $roleId = AdminRoleUsers::where('user_id', $uid)->value('role_id');
        if ($roleId == 1) {
            return true;
        }
        $permissions = AdminRolePermissions::getAllPermissions($roleId);
        if (in_array($targetId, $permissions)) {
            return true;
        }
        return false;

    }
    protected static function checkPermission($uid, $slug): bool
    {
        $permissionId = AdminMenus::getMenuIdBySlug($slug);
        if(self::hasPermission($uid, $permissionId)){
            return true;
        }
        return false;
    }

}