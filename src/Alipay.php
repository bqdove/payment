<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/22
 * Time: 18:01
 */

namespace Notadd\Multipay;

use Latrell\Alipay\Web;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

class Alipay
{
    public function pay(){
        new Web\SdkPayment();
    }
}
