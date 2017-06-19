<?php
/**
 * Created by PhpStorm.
 * User: bc021
 * Date: 17-6-17
 * Time: 下午2:02
 */
namespace Notadd\Multipay\Controllers;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Multipay\Handlers\OrderListHandler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Illuminate\Container\Container;


class QueryController extends Controller
{
    public function orderList(OrderListHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}