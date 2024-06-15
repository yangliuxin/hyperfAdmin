<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Middleware;

use Hyperf\Context\Context;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected HttpResponse $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        $session =  Context::get(SessionInterface::class);
        $user = $session->get("admin");
        $cookie = $this->request->cookie('admin');
        if (!$user && !$cookie) {
            $session->remove("admin");
            $session->clear();
            return $this->response->redirect('/admin/login');
        }
        if($user){
            $user = json_decode($user,true);
        } else {
            $user = json_decode($cookie, true);
            $session->set("admin", $cookie);
        }
        if(!$user){
            $session->remove("admin");
            $session->clear();
            return $response->redirect('/admin/login');
        }

        return $handler->handle($request);
    }
}