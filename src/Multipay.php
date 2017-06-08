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

    /**
      *  The url
      */
    protected $url = '';

    /**
     *
     * The driver is the selected pay-driver;
     *
     */
    public function __construct($app){
        $this->app = $app;
    }

    private function getDriver($name){
        switch($name){
            case 'alipay':
                return new Alipay();
            case 'wechat':
                return new Wechatpay();
            case 'unionpay':
                return new Unionpay();
            default:
                return $this->getDefaultDriver();
        }
    }

    private function getDefaultDriver(){
        return new Alipay();
    }

    
    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */
    public function pay($driver, $way, $para){
        $this->getDriver($driver)->getGateWay($way)->pay($para);

    }

    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */

    public function query($driver, $way, $para){
        $this->getDriver($driver)->getGateWay($way)->query($para);
    }
    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */
    public function refund($driver, $way, $para){
        $this->getDriver($driver)->getGateWay($way)->refund($para);
    }
    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */
    public function webNotify($driver, $way, $para){
        $this->getDriver($driver)->getGateWay($way)->webNotify($para);
    }

    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */
    public function cancel($driver, $way, $para){
        $this->getDriver($driver)->getGateWay($way)->cancel($para);
    }
}