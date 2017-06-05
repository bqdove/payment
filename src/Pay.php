<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/26
 * Time: 10:40
 */

namespace Notadd\Multipay;

use Illuminate\Contracts\Foundation\Application;

class Pay
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

    /**
     *
     * The driver is the selected pay-driver;
     *
     */
    protected $driver;

    public function __construct($app){
        $this->app = $app;
    }
    private function driver($name=null){
        $name=$name ?:$this->getDefaultDriver($name);
        return isset($this->drivers[$name])
            ? $this->drivers[$name]
            : $this->drivers[$name] = $this->getDriver($name);
    }
    private function getDriver($name){
        switch($name){
            case 'alipay':
                return new AliPay();
            case 'wechat':
                return new WechatPay();
            case 'unionpay':
                return new UnionPay();
            default:
                return $this->driver();
        }
    }


    private function getDefaultDriver($name){
        return new Alipay($name);
    }
    /**
     *
     *
     *
     *
     */
    public function pay($driver, $way, $para){
        $this->driver = $this->getDriver($driver)->getGateWay($way)->pay($para);

    }




}