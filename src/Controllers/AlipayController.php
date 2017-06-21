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

    public function set(SetWechatconfHandler $handler,Request $request)
    {
        $this->validate($request,[
            'app_id'=>'required|regex:/\w{18}/',
            'mch_id'=>'required|regex:/\d{10}/',
            'key'=>'required|regex:/\w{32}/',
            'app_secret'=>'required|regex:/\w/',
            'cert'=>'required|mimes:pem',
            'cert_key'=>'required|mimes:pem'
        ],[
            'app_id'=>'app_id不能为空',
            'mch_id'=>'mch_id不能为空',
            'key'=>'key不能为空',
            'app_secret'=>'app_secret不能为空',
            'cert'=>'证书不能为空，证书必须为pem格式的',
            'cert_key'=>'证书不能为空，证书必须为pem格式'
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
