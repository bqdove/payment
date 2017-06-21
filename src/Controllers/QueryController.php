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
use Notadd\Multipay\Handlers\OrderFilterHandler;
use Notadd\Multipay\Handlers\AllHandler;


class QueryController extends Controller
{
    public function orderList(OrderFilterHandler $handler, Request $request)
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

    public function all(AllHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}