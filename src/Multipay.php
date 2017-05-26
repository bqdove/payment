<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/26
 * Time: 10:40
 */

namespace Notadd\Multipay;

use Illuminate\Contracts\Foundation\Application;

class Multipay
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;
    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $drivers = [];

    public function __construct($app){
        $this->app = $app;
    }
    public function driver($name=null){
        $name=$name ?:$this->getDefaultDriver($name);
        return isset($this->drivers[$name])
            ? $this->drivers[$name]
            : $this->drivers[$name] = $this->getDriver($name);
    }
    public function getDriver($name){
        switch($name){
            case 'alipay':
                return new Alipay();
            case 'wechat':
                return new Wechatpay();
            case 'unionpay':
                return new Unionpay();
        }
    }

    public function getDefaultDriver($name){
        return new Alipay($name);
    }
}