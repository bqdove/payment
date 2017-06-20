<?php
/**
 * Created by PhpStorm.
 * User: bc021
 * Date: 17-6-20
 * Time: 上午11:26
 */

namespace Notadd\Multipay\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Multipay\Models\Order;

class AllHandler extends Handler
{
    public function execute()
    {
        $data = Order::paginate(30)->toArray();
        return $this->success()->withData($data)->withMessage('成功返回筛选订单信息');
    }
}