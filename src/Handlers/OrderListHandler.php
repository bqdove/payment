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
use Illuminate\Http\Request;

/*
 * Classs PayHandler
 */
class OrderListHandler extends Handler
{
    public function execute()
    {
        //如果传入参数都为空，那么返回所有的订单信息
        if (! $this->request->input('start') && !$this->request->input('end') && !$this->request->input('search'))
        {
            $allOrders = Order::orderBy('created_at', 'DESC')->paginate(30);

            return $this->success()->withData($allOrders)->withMessage('成功返回所有的订单信息');
        }

        if ((! $this->request->input('start') && !$this->request->input('end')) && $keyword = $this->request->input('search'))
        {
            $filterOrders = Order::where('out_trade_no', 'like', '%'.$keyword)->paginate(30);

            return $this->success()->withData($filterOrders)->withMessage('成功返回筛选订单信息');
        }

        //如果有任意一个参数存在，那么开始时间如果不填写默认为查询当天，结束日期也是一样。
        if ($this->request-input('start'))
        {
            $startTime = $this->request->input('start');
        }else{
            $startTime = date('Y-m-d', time());
        }

        if ($this->request->input('end'))
        {
            $endTime = $this->request->input('end');
        }else{
            $endTime = date('Y-m-d', time());
        }

        if ($startTime > $endTime)
        {
            return $this->withCode('402')->withError('查询开始日期必须早于查询结束日期');
        }

        $query = Order::whereBetween('created_at', [$startTime, $endTime]);

        if ($this->request->input('search'))
        {
            $keyword = $this->request->input('search');

            $filterOrders = $query->where('out_trade_no', 'like', '%'.$keyword)->paginate(30);
        }else{
            $filterOrders = $query->paginate(30);
        }
        if(count($filterOrders))
        {
            return $this->success()->withData($filterOrders)->withMessage('筛选数据返回成功');
        }else{
            return $this->withCode('404')->withError('未找到您需要的数据');
        }

    }
}