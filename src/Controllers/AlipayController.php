<?php
/**
 * This file is part of Notadd.
 *
 * @author AllenGu <674397601@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-05-22 16:26
 */

namespace Notadd\Multipay\Controllers;

use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Multipay\Handlers\GetAlipayconfHandler;
use Notadd\Multipay\Handlers\SetAlipayconfHandler;
use Notadd\Multipay\Handlers\AlipayWebNotifyHandler;
use Notadd\Multipay\Handlers\AlipayReturnNotifyHandler;
use Illuminate\Http\Request;

/**
 * Class MultipayController.
 */
class AlipayController extends Controller
{

    /**
     * Pay handler.
     *
     * @param \Notadd\Multipay\Handlers\GetAlipayconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function get(GetAlipayconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Set handler.
     *
     * @param \Notadd\Multipay\Handlers\SetAlipayconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */

    public function set(SetAlipayconfHandler $handler, Request $request)
    {
        $this->validate($request, [
            'app_id' => 'required|regex:/^[0-9]{16}/',
            'public_key' => 'required',
            'private_key' => 'required'
        ], [
            'app_id.required' => 'app_id不能为空',
            'app_id.regex' => 'app_id必须为16位数字',
            'public_key.required' => '公钥为必填字段',
            'private_key.required' => '私钥为必填字段'
        ]);
        return $handler->toResponse()->generateHttpResponse();
    }

    //异步回调
    public function webNotify(AlipayWebNotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    //同步回调
    public function returnNotify(AlipayReturnNotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

}
