<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Controller\Admin;

use Liuxinyang\HyperfAdmin\Model\AdminRoles;
use Liuxinyang\HyperfAdmin\Model\AdminRoleUsers;
use Liuxinyang\HyperfAdmin\Model\AdminUsers;
use Yangliuxin\Utils\Utils\ServiceConstant;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Illuminate\Support\Str;

#[Controller]
class UsersController extends AbstractAdminController
{
    #[RequestMapping('/admin/users', methods: ['GET'])]
    public function listUsers()
    {
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }
        $usersList = AdminUsers::orderBy('id', 'asc')->paginate(20);
        $this->bladeData['usersList'] = $usersList;
        $this->bladeData['pageNo'] = $usersList->currentPage();
        $totalPages = floor($usersList->total() / $usersList->perPage()) + ($usersList->total() % $usersList->perPage()) == 0 ? 0 : 1;
        $this->bladeData['totalPages'] = $totalPages;
        $this->bladeData['total'] = $usersList->total();
        $this->bladeData['pageNums'] = $usersList->perPage();
        return $this->render->render('admin/users/list', $this->bladeData);
    }

    #[RequestMapping('/admin/users/create', methods: ['GET', 'POST'])]
    public function createUsers()
    {
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }

        $data = new AdminUsers();
        $data['roles'] = 0;
        $this->bladeData["error"] = [];
        $this->bladeData['data'] = $data;
        $this->bladeData['roles'] = AdminRoles::pluck('name', 'id');
        if ($this->request->isMethod("POST")) {
            $csrf_token = $this->request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $this->session->get('csrf_token')){
                $errors['username'] = 'csrf token error';
                return $this->render->render('admin/users/edit', ['error' => $errors]);
            }
            if ($this->request->post("username") == '') {
                $errors['username'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/users/edit', $this->bladeData);
            }
            if ($this->request->post("password") == '') {
                $errors['password'] = '密码不许为空';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/users/edit', $this->bladeData);
            }
            $username = $this->request->post('username');
            $name = $this->request->post('name');
            $password = $this->request->post('password');
            $avatarFile = $this->request->file('avatar_file');
            $userData['username'] = $username;
            if ($name) {
                $userData['name'] = $name;
            }
            $userData['salt'] = Str::random(8);
            $userData['password'] = md5($password . $userData['salt']);

            $path = $this->dealFiletype($avatarFile);
            if ($path) {
                $userData['avatar'] = $path;
            } else {
                $userData['avatar'] = '';
            }

            $adminUser = AdminUsers::create($userData);
            if($this->request->input("roles")){
                AdminRoleUsers::create([
                    'user_id' => $adminUser['id'],
                    'role_id' => intval($this->request->post("roles"))
                ]);
            }

            return $this->response->redirect("/admin/users");
        }
        return $this->render->render('admin/users/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/users/edit/{id}', methods: ['GET', 'POST'])]
    public function editUsers($id)
    {
        if (!$id) {
            return $this->response->redirect('/admin/users');
        }
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }
        $data = AdminUsers::find($id)->toArray();
        $data['roles'] = AdminRoleUsers::getRoleIdByUserId($id);
        $this->bladeData["error"] = [];
        $this->bladeData['data'] = $data;
        $this->bladeData['roles'] = AdminRoles::pluck('name', 'id');
        if ($this->request->isMethod("POST")) {
            $csrf_token = $this->request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $this->session->get('csrf_token')){
                $errors['username'] = 'csrf token error';
                return $this->render->render('admin/users/edit', ['error' => $errors]);
            }
            if ($this->request->post("username") == '') {
                $errors['username'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/users/edit', $this->bladeData);
            }
            if ($this->request->post("username") != $data['username']) {
                $errors['username'] = '用户名不允许修改';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/users/edit', $this->bladeData);
            }
            if ($this->request->post("password") == '') {
                $errors['password'] = '密码不许为空';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/users/edit', $this->bladeData);
            }
            $name = $this->request->post('name');
            $password = $this->request->post('password');
            $avatarFile = $this->request->file('avatar_file');
            $userData = [];
            if ($name) {
                $userData['name'] = $name;
            }
            if ($password && $password != $user['password']) {
                $userData['password'] = md5($password . $data['salt']);
            }
            $path = $this->dealFiletype($avatarFile);
            if ($path) {
                $userData['avatar'] = $path;
            }
            AdminUsers::where('id', $id)->update($userData);
            if($this->request->input("roles")){
                AdminRoleUsers::where('user_id', $id)->delete();
                AdminRoleUsers::create([
                    'user_id' => $id,
                    'role_id' => intval($this->request->post("roles"))
                ]);
            }
            return $this->response->redirect("/admin/users");
        }

        return $this->render->render('admin/users/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/users/delete/{id}', methods: ['DELETE'])]
    public function deleteUsers($id)
    {
        if (!$id) {
            return $this->response->redirect('/admin/users');
        }
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return ServiceConstant::error(ServiceConstant::MSG_TOKEN_ERROR);
        }
        AdminUsers::where('id', $id)->delete();
        AdminRoleUsers::where('user_id', $id)->delete();
        return ServiceConstant::success();
    }
}