<?php
/**
 * Created by PhpStorm.
 * User: bc021
 * Date: 17-6-17
 * Time: 下午2:18
 */
namespace Notadd\Multipay\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Multipay\Models\Order;


/*
 * Classs PayHandler
 */
class ListHandler extends Handler
{
    public function execute()
    {
        $startTime = $this->request->query('start');

        $endTime = $this->request->query('end');

        $keyword = $this->request->input('search');

        if ($startTime || $endTime || $keyword)
        {
            
        }else{
            $allOrders = Order::all();

            return $this->success()->withData($allOrders)->withMessage('成功返回所有的订单信息');
        }
    }
}