<?php

/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/22
 * Time: 18:01
 */

namespace Notadd\Multipay;

Interface Pay
{
    //支付申请
    public function pay();
    //异步通知
    public function webNotify();
    // 同步通知
    public function webReturn();
}
