<?php
/**
 * The file is part of Notadd
 *
 * @author: AllenGu<674397601@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime: 17-7-7 上午11:06
 */

namespace Notadd\Multipay;


interface Payment
{
    /**
     * 判断网关(具体的支付方式)
     *
     * @param  string  $gatewayName
     * @return $this
     */
    public function getGateWay($gatewayName);

    /**
     * 支付方法
     *
     * @return array
     */

    public function pay();

    /**
     * 查询方法
     *
     * @return array
     */

    public function query();

    /**
     * 退款方法
     *
     * @return array
     */

    public function refund();


    /**
     * 取消支付方法（仍未完成）
     *
     * @return array
     */

    public function cancel();
}