<?php
/**
 * This file is part of Notadd.
 *
 * @author AllenGu <674397601@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-05-22 16:19
 */
namespace Notadd\Multipay;

use Illuminate\Events\Dispatcher;
use Notadd\Multipay\Listeners\CsrfTokenRegister;
use Notadd\Multipay\Listeners\RouteRegister;
use Notadd\Foundation\Extension\Abstracts\Extension as AbstractExtension;

/**
 * Class Extension.
 */
class Extension extends AbstractExtension
{
    /**
     * Boot provider.
     */
    public function boot()
    {
        $this->app->make(Dispatcher::class)->subscribe(CsrfTokenRegister::class);
        $this->app->make(Dispatcher::class)->subscribe(RouteRegister::class);
    }
}
