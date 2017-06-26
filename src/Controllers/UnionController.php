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
use Notadd\Multipay\Handlers\GetUnionpayconfHandler;
use Notadd\Multipay\Handlers\SetUnionpayconfHandler;
use Notadd\Multipay\Handlers\UnionWebNotifyHandler;
use Illuminate\Http\Request;

/**
 * Class MultipayController.
 */
class UnionController extends Controller
{
    /**
     * Get handler.
     *
     * @param \Notadd\Multipay\Handlers\GetUnionpayconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function get(GetUnionpayconfHandler $handler)
    {

        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Set handler.
     *
     * @param \Notadd\Multipay\Handlers\SetUnionpayconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */
    public function set(SetUnionpayconfHandler $handler, Request $request)
    {
        $this->validate($request, [
            'mer_id' => 'required|regex:/\d{15}/',
            'key' => 'required|regex:/\w/',
            'cert' => 'required|mimes:pfx',
        ], [
            'mer_id.required' => 'mer_id不能为空',
            'mer_id.regex' => 'mer_id必须为15位数字',
            'key.required' => 'key不能为空',
            'key.regex' => 'key格式为字母或者数字',
            'cert.required' => '不能为空',
            'cert.mimes' => '证书必须为pfx格式'
        ]);
        return $handler->toResponse()->generateHttpResponse();
    }

    public function webnotify(UnionWebNotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}
