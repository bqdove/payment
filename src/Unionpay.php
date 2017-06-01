<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/23
 * Time: 18:18
 */

namespace Notadd\Multipay;

use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Illuminate\Container\Container;
use Omnipay\Omnipay;

class Unionpay
{
    protected $settings;
    public function __construct(){
        $this->settings = Container::getInstance()->make(SettingsRepository::class);

    }
    //获取配置
   public function getconfig($config){
        return $this->settings->get($config);
   }

   public function pay(){
       $gateway = Ominpay::gateway('unionpay');
   }
}