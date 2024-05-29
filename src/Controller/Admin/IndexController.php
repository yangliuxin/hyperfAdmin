<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Controller\Admin;

use Liuxinyang\HyperfAdmin\Model\AdminUsers;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

#[Controller]
class IndexController extends AbstractAdminController
{
    #[RequestMapping('/admin/index', methods: ['GET'])]
    public function index()
    {
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }
        return $this->render->render('admin/index', $this->bladeData);
    }

    #[RequestMapping('/admin/profile', methods: ['GET', 'POST'])]
    public function profile()
    {
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }
        $user = AdminUsers::find($user['id']);
        $this->bladeData['data'] = $user;
        if ($this->request->isMethod("POST")) {
            $name = $this->request->post('name');
            $password = $this->request->post('password');
            $avatarFile = $this->request->file('avatar_file');
            $userData = [];
            if ($name) {
                $userData['name'] = $name;
            }
            if ($password && $password != $user['password']) {
                $userData['password'] = md5($password . $user['salt']);
            }
            $path = $this->dealFiletype($avatarFile);
            if ($path) {
                $userData['avatar'] = $path;
            }
            AdminUsers::where('id', $user['id'])->update($userData);
            $this->session->set("admin", json_encode($user, 320));
            return $this->response->redirect('/admin/profile');
        }
        return $this->render->render('admin/profile', $this->bladeData);
    }

}