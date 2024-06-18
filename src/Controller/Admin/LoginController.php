<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Controller\Admin;

use Liuxinyang\HyperfAdmin\Model\AdminUsers;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

#[Controller]
class LoginController extends AbstractAdminController
{

    #[RequestMapping('/admin/login', methods: ['GET'])]
    public function login()
    {
        if(!$this->session->get('csrf_token')){
            $this->session->set('csrf_token', str_random(32));
        }
        $this->bladeData = [];
        $this->bladeData['_token'] = $this->session->get('csrf_token');
        return $this->render->render('admin/login', $this->bladeData);
    }

    #[RequestMapping('/admin/login', methods: ['POST'])]
    public function loginPost()
    {
        if(!$this->session->get('csrf_token')){
            $this->session->set('csrf_token', str_random(32));
        }
        $this->bladeData['_token'] = $this->session->get('csrf_token');
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $remember = $this->request->input('remember');
        $csrf_token = $this->request->input('csrf_token');
        if(!$csrf_token || $csrf_token != $this->session->get('csrf_token')){
            $this->bladeData['error']['username'] = 'csrf token error';
            return $this->render->render('admin/login', $this->bladeData);
        }
        if (!$username) {
            $this->bladeData['error']['username'] = '用户名不许为空';
            return $this->render->render('admin/login', $this->bladeData);
        }
        if (!$password) {
            $this->bladeData['error']['password'] = '请输入密码';
            return $this->render->render('admin/login', $this->bladeData);
        }

        $user = AdminUsers::where('username', $username)->first();
        if (!$user) {
            $this->bladeData['error']['username'] = '用户不存在';
            return $this->render->render('admin/login', $this->bladeData);
        }
        if (md5($password . $user['salt']) != $user['password']) {
            $this->bladeData['error']['password'] = '密码不正确';
            return $this->render->render('admin/login', $this->bladeData);
        }

        $this->session->set("admin", json_encode($user, 320));
        if($remember){
            $cookie = new Cookie('admin', json_encode($user, 320), 3600 * 24 * 7);
            return $this->response->withCookie($cookie)->redirect('/admin/index');
        } else {
            return $this->response->redirect('/admin/index');
        }
    }

    #[RequestMapping('/admin/logout', methods: ['POST', 'get'])]
    public function logout()
    {
        $this->session->remove("admin");
        $this->session->clear();
        setcookie('admin', '', time() - 3600);
        $cookie = new Cookie('admin', '', time() - 3600);
        $this->bladeData = [];
        return $this->response->withCookie($cookie)->redirect('/admin/login');
    }


}