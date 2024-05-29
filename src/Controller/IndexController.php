<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Model\AdminMenus;
use Yangliuxin\Utils\Utils\ServiceConstant;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

#[Controller]
class IndexController extends AbstractController
{
    #[RequestMapping(path: '/', methods: 'get,post')]
    public function index()
    {

        return $this->render->render('index', $this->bladeData);
    }
}
