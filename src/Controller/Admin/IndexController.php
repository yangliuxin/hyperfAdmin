<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Controller\Admin;

use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Annotation\Middleware;
use Liuxinyang\HyperfAdmin\Annotaion\PermissionCheck;
use Liuxinyang\HyperfAdmin\Middleware\AuthMiddleware;
use Liuxinyang\HyperfAdmin\Model\AdminStats;
use Liuxinyang\HyperfAdmin\Model\AdminUsers;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

#[Controller]
#[Middleware(AuthMiddleware::class)]
class IndexController extends AbstractAdminController
{
    #[RequestMapping('/admin/index', methods: ['GET', 'POST', 'HEADER'])]
    #[PermissionCheck('home')]
    public function index()
    {
        $this->_init();
        $this->bladeData['statisticsShow'] = config('hyperfAdmin.statistics.show');
        $this->bladeData['statistics'] = config('hyperfAdmin.statistics.data');
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
        $this->_init();
        $user = $this->user;
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