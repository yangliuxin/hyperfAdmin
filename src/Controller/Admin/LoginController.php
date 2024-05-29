<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\AdminUsers;
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
        $this->bladeData['_token'] = $this->session->get('csrf_token');
        return $this->render->render('admin/login', $this->bladeData);
    }

    #[RequestMapping('/admin/login', methods: ['POST'])]
    public function loginPost()
    {
        $errors = [];
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $remember = $this->request->input('remember');
        $csrf_token = $this->request->input('csrf_token');
        if(!$csrf_token || $csrf_token != $this->session->get('csrf_token')){
            $errors['username'] = 'csrf token error';
            return $this->render->render('admin/login', ['error' => $errors]);
        }
        if (!$username) {
            $errors['username'] = '用户名不许为空';
            return $this->render->render('admin/login', ['error' => $errors]);
        }
        if (!$password) {
            $errors['password'] = '请输入密码';
            return $this->render->render('admin/login', ['error' => $errors]);
        }

        $user = AdminUsers::where('username', $username)->first();
        if (!$user) {
            $errors['username'] = '用户不存在';
            return $this->render->render('admin/login', ['error' => $errors]);
        }
        if (md5($password . $user['salt']) != $user['password']) {
            $errors['password'] = '密码不正确';
            return $this->render->render('admin/login', ['error' => $errors]);
        }

        $this->session->set("admin", json_encode($user, 320));
        if($remember){
            $cookie = new Cookie('admin', json_encode($user, 320));
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

        return $this->response->withCookie(new Cookie('admin', ''))->redirect('/admin/login');
    }


}