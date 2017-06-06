<?php
/**
 * This file is part of Notadd.
 *
 * @author AllenGu <674397601@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-05-22 16:24
 */
namespace Notadd\Multipay\Listeners;

use Notadd\Multipay\Controllers\AlipayController;
use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;
use Notadd\Multipay\Controllers\UnionController;
use Notadd\Multipay\Controllers\WechatController;
use Notadd\Multipay\Controllers\UploadController;

/**
 * Class RouteRegister.
 */
class RouteRegister extends AbstractRouteRegister
{
    /**
     * Handle Route Registrar.
     */
    public function handle()
    {
            $this->router->group(['middleware' => ['cross', 'web']], function () {
                $this->router->post('get',AlipayController::class . '@get');
                $this->router->post('set', AlipayController::class . '@set');
                $this->router->post('get',WechatController::class . '@get');
                $this->router->post('set',WechatController::class . '@set');
                $this->router->post('get',UnionController::class . '@get');
                $this->router->post('set',UnionController::class . '@set');
                $this->router->post('upload',UploadController::class .'@handle');
            });


    }
}
