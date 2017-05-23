<?php
/**
 * This file is part of Notadd.
 *
 * @author AllenGu <674397601@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-05-22 16:24
 */
namespace Notadd\Multipay\Listeners;

use Notadd\Multipay\Controllers\MultipayController;
use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;

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
<<<<<<< HEAD
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api/baidu'], function () {
=======
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api/multipay'], function () {
>>>>>>> e7b4dc3dd66ede1c1abe469608c39c47296321d3
            $this->router->post('get', MultipayController::class . '@get');
            $this->router->post('set', MultipayController::class . '@set');
        });
    }
}
