# hyperfAdmin
admin for hyperf
### 使用方法 ###
#### 1、建立hyperf项目 ####
#### 2、composer require liuxinyang/hyperf-admin ####
#### 3、php bin/hyperf.php vendor:publish liuxinyang/hyperf-admin ####
#### 4、php bin/hyperf.php admin:init ####
#### 5、route.php 里填写如下路由 ####
    Router::get('/admin', [\Liuxinyang\HyperfAdmin\Controller\Admin\IndexController::class, 'index'], ['middleware' => [\Liuxinyang\HyperfAdmin\Middleware\AuthMiddleware::class]]);
    Router::get('/admin/', [\Liuxinyang\HyperfAdmin\Controller\Admin\IndexController::class, 'index'], ['middleware' => [\Liuxinyang\HyperfAdmin\Middleware\AuthMiddleware::class]]);
#### 6、后台首页统计部分 ####
    需记录访问请求日志
    AdminStats::log($uri, $ip, $province, $city);
#### 7、方法级检测权限 ####
    #[PermissionCheck('{slug}')]
    或
    if (!$this->checkPermission($user['id'], '{slug}')) {
        return $this->render->render('/admin/noauth', $this->bladeData);
    }