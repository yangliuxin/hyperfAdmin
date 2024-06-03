<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Controller\Admin;

use Hyperf\HttpMessage\Cookie\Cookie;
use Liuxinyang\HyperfAdmin\Model\AdminStats;
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
        if(!$user){
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->withCookie(new Cookie('admin', ''))->redirect('/admin/login');
        }
        if(!$this->checkPermission($user['id'],'home')){
            return $this->render->render('/admin/noauth', $this->bladeData);
        }
        $this->bladeData['statistics'] = [
            [
                'icon' => 'ion-ios-gear-outline',
                'class' => 'bg-aqua',
                'title' => '占位符1',
                'data' => 10000
            ],
            [
                'icon' => 'fa-google-plus',
                'class' => 'bg-red',
                'title' => '占位符2',
                'data' => 20000
            ],
            [
                'icon' => 'ion-ios-cart-outline',
                'class' => 'bg-green',
                'title' => '占位符3',
                'data' => 30000
            ],
            [
                'icon' => 'ion-ios-people-outline',
                'class' => 'bg-yellow',
                'title' => '占位符4',
                'data' => 40000
            ],
        ];
        $this->bladeData['hotUriList'] = AdminStats::getHotUrlList();
        $data = AdminStats::getPieData();
        $pieChartData = [
            'title' => '访问地区统计(pv)',
            'legends' => $data['legends'],
            'seriesName' => '访问地区统计(PV)',
            'seriesData' => $data['seriesData']
        ];
        $this->bladeData['pieChartData'] = json_encode($pieChartData);

        $data = AdminStats::getLineStatData();
        $lineChartData = [
            'title' => '访问统计折线图',
            'legend' => [
                'data' => ['UV', 'PV'],
                'selected' => ['UV' => true, 'PV' => true,]
            ],
            'yAxisName' => '访问量',
            'xAxisData' => ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            'seriesData' => [
                [
                    'name' => 'UV',
                    'type' => 'line',
                    'stack' => '总量',
                    'data' => $data[1]
                ],
                [
                    'name' => 'PV',
                    'type' => 'line',
                    'stack' => '总量',
                    'data' => $data[0]
                ],
            ]
        ];

        $this->bladeData['lineChartData'] = json_encode($lineChartData);
        return $this->render->render('admin/index', $this->bladeData);
    }

    #[RequestMapping('/admin/profile', methods: ['GET', 'POST'])]
    public function profile()
    {
        $user = $this->_init();
        if(!$user){
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->withCookie(new Cookie('admin', ''))->redirect('/admin/login');
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