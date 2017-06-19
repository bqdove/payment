<?php
/**
 * Created by PhpStorm.
 * User: bc021
 * Date: 17-6-17
 * Time: 下午2:02
 */
namespace Notadd\Multipay\Controllers;

use Illuminate\Http\Request;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Multipay\Handlers\OrderListHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Illuminate\Container\Container;

class QueryController extends Controller
{
    public function orderList(OrderListHandler $handler, Request $request)
    {
        $this->validate($request,[
            'start'=>'date',
            'end'=>'date',
            'keyword'=>'numeric'
        ],[
            'start'=>'查询开始时间必须日期格式',
            'end'=>'查询开始时间必须日期格式',
            'keyword'=>'查询单号必须为纯数字'
        ]);

        return $handler->toResponse()->generateHttpResponse();
    }
}