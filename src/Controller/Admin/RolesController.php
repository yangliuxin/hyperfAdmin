<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\AdminMenus;
use App\Model\AdminRolePermissions;
use App\Model\AdminRoles;
use Yangliuxin\Utils\Utils\ServiceConstant;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

#[Controller]
class RolesController extends AbstractAdminController
{
    #[RequestMapping('/admin/roles', methods: ['GET'])]
    public function listRoles()
    {
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }
        $rolesList = AdminRoles::orderBy('id', 'asc')->paginate(20);
        $this->bladeData['rolesList'] = $rolesList;
        $this->bladeData['pageNo'] = $rolesList->currentPage();
        $totalPages = floor($rolesList->total() / $rolesList->perPage()) + ($rolesList->total() % $rolesList->perPage()) == 0 ? 0 : 1;
        $this->bladeData['totalPages'] = $totalPages;
        $this->bladeData['total'] = $rolesList->total();
        $this->bladeData['pageNums'] = $rolesList->perPage();
        return $this->render->render('admin/roles/list', $this->bladeData);
    }

    #[RequestMapping('/admin/roles/create', methods: ['GET', 'POST'])]
    public function createRoles()
    {
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }

        $jsTreeList = [];
        $menuList = AdminMenus::all();
        foreach ($menuList as $key => $menu) {
            $jsTreeList[] = [
                'id' => strval($menu['id']),
                'text' => $menu['title'],
                'parent' => $menu['parent_id'] == 0 ? '#' : $menu['parent_id'],
                'icon' => $menu['type'] == 1 ? 'fa ' . $menu['icon'] : ' glyphicon glyphicon-file ',
                'state' => ['opened' => true, 'disabled' => false, 'selected' => false],
            ];
        }
        $data = new AdminRoles();
        $this->bladeData['treeData'] = json_encode($jsTreeList);
        $this->bladeData["error"] = [];
        $this->bladeData['data'] = $data;
        if ($this->request->isMethod("POST")) {
            $csrf_token = $this->request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $this->session->get('csrf_token')){
                $errors['name'] = 'csrf token error';
                return $this->render->render('admin/roles/edit', ['error' => $errors]);
            }
            if ($this->request->post("name") == '') {
                $errors['name'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/roles/edit', $this->bladeData);
            }
            $permissions = $this->request->post("permissions");
            $role = AdminRoles::create([
                'name' => $this->request->post("name"),
                'slug' => $this->request->post("slug"),
            ]);
            AdminRolePermissions::where('role_id', $role['id'])->delete();
            $permissions = explode(",", $permissions);
            foreach ($permissions as $permission) {
                AdminRolePermissions::create([
                    'role_id' => $role['id'],
                    'permission_id' => $permission,
                ]);
            }
            return $this->response->redirect("/admin/roles");
        }
        return $this->render->render('admin/roles/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/roles/edit/{id}', methods: ['GET', 'POST'])]
    public function editRoles($id)
    {
        if (!$id) {
            return $this->response->redirect('/admin/roles');
        }
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }
        $jsTreeList = [];
        $menuList = AdminMenus::all();
        foreach ($menuList as $key => $menu) {
            $jsTreeList[] = [
                'id' => strval($menu['id']),
                'text' => $menu['title'],
                'parent' => $menu['parent_id'] == 0 ? '#' : $menu['parent_id'],
                'icon' => $menu['type'] == 1 ? 'fa ' . $menu['icon'] : ' glyphicon glyphicon-file ',
                'state' => ['opened' => true, 'disabled' => false, 'selected' => false],
            ];
        }
        $data = AdminRoles::find($id);
        $this->bladeData['treeData'] = json_encode($jsTreeList);
        $this->bladeData["error"] = [];
        $this->bladeData['data'] = $data;
        $permissions = AdminRolePermissions::where('role_id', $id)->pluck("permission_id")->toArray();
        $this->bladeData["permissions"] = json_encode($permissions);
        if ($this->request->isMethod("POST")) {
            $csrf_token = $this->request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $this->session->get('csrf_token')){
                $errors['name'] = 'csrf token error';
                return $this->render->render('admin/roles/edit', ['error' => $errors]);
            }
            if ($this->request->post("name") == '') {
                $errors['name'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/roles/edit', $this->bladeData);
            }

            AdminRoles::where('id', $id)->update([
                'name' => $this->request->input("name"),
                'slug' => $this->request->input("slug"),
            ]);
            $permissions = $this->request->post("permissions");
            AdminRoles::where('id', $id)->update([
                'name' => $this->request->post("name"),
                'slug' => $this->request->post("slug"),
            ]);
            AdminRolePermissions::where('role_id', $id)->delete();
            $permissions = explode(",", $permissions);
            foreach ($permissions as $permission) {
                AdminRolePermissions::create([
                    'role_id' => $id,
                    'permission_id' => $permission,
                ]);
            }
            return $this->response->redirect("/admin/roles");
        }

        return $this->render->render('admin/roles/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/roles/delete/{id}', methods: ['DELETE'])]
    public function deleteRoles($id)
    {
        if (!$id) {
            return $this->response->redirect('/admin/roles');
        }
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return ServiceConstant::error(ServiceConstant::MSG_TOKEN_ERROR);
        }
        AdminRoles::where('id', $id)->delete();
        AdminRolePermissions::where('role_id', $id)->delete();
        return ServiceConstant::success();
    }
}