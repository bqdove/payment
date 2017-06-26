<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/26
 * Time: 10:40
 */

namespace Notadd\Multipay;


use Notadd\Multipay\Handlers\GetAlipayconfHandler;
use Notadd\Multipay\Handlers\GetUnionpayconfHandler;
use Notadd\Multipay\Handlers\GetWechatconfHandler;

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
     *  The $config
     */
    protected $config;

    /**
     *
     * The driver is the selected pay-driver;
     *
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    private function getDriver($name)
    {
        switch ($name) {
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

    private function getDefaultDriver()
    {
        return new Alipay();
    }


    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */
    public function pay()
    {
        if (!array_key_exists('driver', $_POST) || !array_key_exists('way', $_POST)) {
            return json_encode(['code' => 402, 'msg' => '缺少驱动或者网关参数']);
        }
        $driver = $_POST['driver'];
        $way = $_POST['way'];
        $data = $this->getDriver($driver)->getGateWay($way)->pay();
        return $data;
    }

    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */

    public function query()
    {
        if (!array_key_exists('driver', $_POST) || !array_key_exists('way', $_POST)) {
            return json_encode(['code' => 402, 'msg' => '缺少驱动或者网关参数']);
        }
        $driver = $_POST['driver'];
        $way = $_POST['way'];
        $data = $this->getDriver($driver)->getGateWay($way)->query();
        return $data;
    }

    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */
    public function refund()
    {
        if (!array_key_exists('driver', $_POST) || !array_key_exists('way', $_POST)) {
            return json_encode(['code' => '402', 'msg' => '缺少驱动或者网关参数']);
        }
        $driver = $_POST['driver'];
        $way = $_POST['way'];
        $data = $this->getDriver($driver)->getGateWay($way)->refund();
        return $data;
    }

    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     */
    public function webNotify($driver, $way)
    {

        $this->getDriver($driver)->getGateWay($way)->webNotify();
    }

    /**
     * @param  String $driver
     * @param  String $way
     * @param  Array $para
     *
     */
    public function cancel($driver, $way, $para)
    {
        $this->getDriver($driver)->getGateWay($way)->cancel($para);
    }

//    public function use($config)
//    {
//        switch($config){
//            case 'alipay':
//                return new GetAlipayconfHandler();
//            case 'wechat':
//                return new GetWechatconfHandler();
//            case 'unionpay':
//                return new GetUnionpayconfHandler();
//        }
//    }

}