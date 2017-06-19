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
    public function set(SetAlipayconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function webnotify(AlipayWebNotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

}
