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
use Notadd\Multipay\Controllers\PayController;
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
            //http://pay.ibenchu.xyz:8080/pay?gate_way=wechat&way=Alipay_Express&money=100&sign=RSA2

        $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'api/multipay'], function () {
            $this->router->get('pay', PayController::class. '@pay');
            $this->router->get('query', PayController::class. '@query');
            $this->router->get('refund', PayController::class. '@refund');
            $this->router->get('cancel', PayController::class. '@cancel');

        });
        $this->router->get('upload', UploadController::class. '@upload');
        $this->router->get('webnotify', PayController::class. '@webNotify');
        $this->router->post('execute', UploadController::class. '@execute');

    }
}
