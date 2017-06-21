<?php
/**
 * This file is part of Notadd.
 *
 * @author AllenGu <674397601@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-05-22 16:24
 */
namespace Notadd\Multipay\Listeners;

use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;
use Notadd\Multipay\Controllers\AlipayController;
use Notadd\Multipay\Controllers\PayController;
use Notadd\Multipay\Controllers\QueryController;
use Notadd\Multipay\Controllers\UploadController;
use Notadd\Multipay\Controllers\WechatController;
use Notadd\Multipay\Controllers\UnionController;


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
        $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'api/multipay'], function () {
            $this->router->get('pay', PayController::class. '@pay');
            $this->router->get('query', PayController::class. '@query');
            $this->router->get('refund', PayController::class. '@refund');
            $this->router->get('cancel', PayController::class. '@cancel');
            $this->router->get('test', PayController::class. '@test');
            $this->router->post('order', QueryController::class.'@orderList');
            $this->router->get('order',QueryController::class. '@all');
            $this->router->post('upload', UploadController::class. '@execute');

            $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'alipay'], function () {
                $this->router->post('set',AlipayController::class.'@set');
                $this->router->get('get',AlipayController::class.'@get');
                //异步回调
                $this->router->post('webnotify',AlipayController::class. '@webNotify');
                //同步回调
                $this->router->get('notify',AlipayController::class.'@returnNotify');
            });

            $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'wechat'], function () {
                $this->router->post('set',WechatController::class.'@set');
                $this->router->get('get',WechatController::class.'@get');
                $this->router->post('webnotify',WechatController::class. '@webNotify');
            });

            $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'union'], function () {
                $this->router->post('set',UnionController::class.'@set');
                $this->router->post('get',UnionController::class.'@get');
                $this->router->post('webnotify',UnionController::class.'@webNotify');
            });
        });
    }
}
