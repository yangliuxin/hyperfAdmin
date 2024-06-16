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
#### 8、AppExceptionHandler设置 ####
    $path = ApplicationContext::getContainer()->get(RequestInterface::class)->getPathInfo();
    if(str_starts_with($path, "/api/")){
        $this->stopPropagation();
        return $response->withHeader('Content-type', 'application/json')->withStatus($throwable->getCode())->withBody(new SwooleStream(json_encode(ServiceConstant::result($throwable->getCode(), $throwable->getMessage()), 320)));
    } else {
        $bladeData = [];
        $this->stopPropagation();
        if($throwable->getCode() == 403){
            $session =  Context::get(SessionInterface::class);
            $userSession = $session->get("admin");
            if($userSession){
                $user = json_decode($userSession, true);
                $bladeData['user'] = $user;

                $menuDataList = AdminMenus::where('type', 1)->get()->toArray();
                foreach ($menuDataList as $key => $menu) {
                    if ($menu['uri'] == '') {
                        $menuDataList[$key]['uri'] = '/admin/#';
                    } elseif ($menu['uri'] == '/') {
                        $menuDataList[$key]['uri'] = '/admin';
                    } else {
                        $menuDataList[$key]['uri'] = '/admin/' . $menu['uri'];
                    }
                    if (!self::hasPermission($user['id'], $menu['id'])) {
                        unset($menuDataList[$key]);
                    }
                }

                $bladeData['menuDataList'] = TreeUtils::getTree($menuDataList);
                if(!$session->get('csrf_token')){
                    $session->set('csrf_token', str_random(32));
                }
                $bladeData['_token'] = $session->get('csrf_token');
            }
            return ApplicationContext::getContainer()->get(RenderInterface::class)->render('admin/noauth', $bladeData);
        } else {
            return $response->withHeader('Server', 'Hyperf')->withStatus($throwable->getCode())->withBody(new SwooleStream($throwable->getMessage()));
        }
    }