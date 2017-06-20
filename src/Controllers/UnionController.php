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
    public function set(SetUnionpayconfHandler $handler ,Request $request)
    {
        $this->validate($request,[
            'mer_id'=>'required',
            'key'=>'required',
        ],[
            'mer_id'=>'mer_id不能为空',
            'key'=>'key不能为空',
        ]);
        return $handler->toResponse()->generateHttpResponse();
    }

    public function webnotify(UnionWebNotifyHandler $handler )
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}
