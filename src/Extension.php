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
        $this->publishes([
            realpath(__DIR__ . '/../resources/mixes/administration/dist/assets/extensions/pay') => public_path('assets/extensions/pay'),
        ], 'public');
    }
    /**
     * Description of extension
     *
     * @return string
     */
    public static function description()
    {
        return '多种支付方式的配置和管理。';
    }

    /**
     * Installer for extension.
     *
     * @return \Closure
     */
    public static function install()
    {
        return function () {
            return true;
        };
    }

    /**
     * Name of extension.
     *
     * @return string
     */
    public static function name()
    {
        return '多支付插件';
    }

    /**
     * Get script of extension.
     *
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function script()
    {
        return asset('assets/extensions/pay/js/extension.min.js');
    }

    /**
     * Get stylesheet of extension.
     *
     * @return array
     */
    public static function stylesheet()
    {
        return [
            asset('assets/extensions/pay/css/extension.min.css'),
        ];
    }

    /**
     * Uninstall for extension.
     *
     * @return \Closure
     */
    public static function uninstall()
    {
        return function () {
            return true;
        };
    }

    /**
     * Version of extension.
     *
     * @return string
     */
    public static function version()
    {
        return '0.1.0';
    }

    public function register(){
        return $this->registerPay();
    }

    public function registerPay(){
            $this->app->singleton('Pay', function($app){
            return new Pay($app);
        });
    }
}
