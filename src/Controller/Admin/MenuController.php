<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Controller\Admin;


use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Annotation\Middleware;
use Liuxinyang\HyperfAdmin\Annotaion\PermissionCheck;
use Liuxinyang\HyperfAdmin\Middleware\AuthMiddleware;
use Liuxinyang\HyperfAdmin\Model\AdminMenus;
use Yangliuxin\Utils\Utils\ServiceConstant;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Yangliuxin\Utils\Utils\TreeUtils;

#[Controller]
#[Middleware(AuthMiddleware::class)]
class MenuController extends AbstractAdminController
{
    #[RequestMapping('/admin/menu', methods: ['GET'])]
    #[PermissionCheck('menu')]
    public function listMenu()
    {
        $this->_init();
        $menuList = AdminMenus::all();
        $menuList = TreeUtils::getTree($menuList);
        $this->bladeData['menuList'] = $menuList;
        return $this->render->render('admin/menu/list', $this->bladeData);

    }

    #[RequestMapping('/admin/menu/create', methods: ['GET', 'POST'])]
    #[PermissionCheck('menu')]
    public function createMenu()
    {
        $this->_init();
        $selectMenuOptions = AdminMenus::where('type', 1)->get()->toArray();
        $selectMenuOptions = TreeUtils::getTree($selectMenuOptions);
        $this->bladeData['selectMenuOptions'] = $selectMenuOptions;
        $data = new AdminMenus();
        $this->bladeData['data'] = $data;
        $this->bladeData["error"] = [];
        if ($this->request->isMethod("POST")) {
            $csrf_token = $this->request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $this->session->get('csrf_token')){
                $errors['title'] = 'csrf token error';
                return $this->render->render('admin/menu/edit', ['error' => $errors]);
            }
            if ($this->request->post("title") == '') {
                $errors['title'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/menu/edit', $this->bladeData);
            }

            AdminMenus::create([
                'title' => $this->request->input("title"),
                'uri' => $this->request->input("uri"),
                'parent_id' => $this->request->input("parent_id"),
                'type' => $this->request->input("type"),
                'icon' => $this->request->input("icon"),
                'slug' => $this->request->input("slug"),
                'sort' => $this->request->input("sort"),
            ]);
            return $this->response->redirect("/admin/menu");
        }

        return $this->render->render('admin/menu/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/menu/edit/{id}', methods: ['GET', 'POST'])]
    #[PermissionCheck('menu')]
    public function editMenu($id)
    {
        if (!$id) {
            return $this->response->redirect('/admin/menu');
        }
        $this->_init();
        $selectMenuOptions = AdminMenus::where('type', 1)->get()->toArray();
        $selectMenuOptions = TreeUtils::getTree($selectMenuOptions);
        $this->bladeData['selectMenuOptions'] = $selectMenuOptions;
        $data = AdminMenus::find($id);
        $this->bladeData['data'] = $data;
        $this->bladeData["error"] = [];
        if ($this->request->isMethod("POST")) {
            $csrf_token = $this->request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $this->session->get('csrf_token')){
                $errors['title'] = 'csrf token error';
                return $this->render->render('admin/menu/edit', ['error' => $errors]);
            }
            if ($this->request->post("title") == '') {
                $errors['title'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return $this->render->render('admin/menu/edit', $this->bladeData);
            }

            AdminMenus::where('id', $id)->update([
                'title' => $this->request->input("title"),
                'uri' => $this->request->input("uri"),
                'parent_id' => $this->request->input("parent_id"),
                'type' => $this->request->input("type"),
                'icon' => $this->request->input("icon"),
                'slug' => $this->request->input("slug"),
                'sort' => $this->request->input("sort"),
            ]);
            return $this->response->redirect("/admin/menu");
        }

        return $this->render->render('admin/menu/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/menu/delete/{id}', methods: ['DELETE'])]
    #[PermissionCheck('menu')]
    public function deleteMenu($id)
    {
        if (!$id) {
            return $this->response->redirect('/admin/menu');
        }
        AdminMenus::where('id', $id)->delete();

        return ServiceConstant::success();
    }

}