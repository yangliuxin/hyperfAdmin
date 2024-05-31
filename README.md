# hyperfAdmin
admin for hyperf
### 使用方法 ###
#### 1、建立hyperf项目 ####
#### 2、composer require liuxinyang/hyperf-admin ####
#### 3、php bin/hyperf.php vendor:publish liuxinyang/hyperf-admin ####
#### 4、php bin/hyperf.php init:data ####
#### 5、route.php 里填写如下路由 ####
    Router::addRoute(['GET', 'POST', 'HEAD'], '/admin', 'Liuxinyang\HyperfAdmin\Controller\Admin\IndexController@index');
    Router::addRoute(['GET', 'POST', 'HEAD'], '/admin/', 'Liuxinyang\HyperfAdmin\Controller\Admin\IndexController@index');
#### 6、后台首页统计部分 ####
    需记录访问请求日志
    AdminStats::log($uri, $ip, $province, $city);