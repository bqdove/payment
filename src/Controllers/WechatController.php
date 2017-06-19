<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/24
 * Time: 11:48
 */

namespace Notadd\Multipay\Controllers;
use Notadd\Multipay\Handlers\GetWechatconfHandler;
use Notadd\Multipay\Handlers\SetWechatconfHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;

use Illuminate\Http\Request;

/**
 * Class WechatconfController.
 */
class WechatController extends Controller
{
    /**
     * Get handler.
     *
     * @param \Notadd\Multipay\Handlers\GetHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function get(GetWechatconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * Set handler.
     *
     * @param \Notadd\MUltipay\Handlers\SetHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     * @throws \Exception
     */
    public function set(SetWechatconfHandler $handler,Request $request)
    {
        $this->validate($request,[
            'app_id'=>'required|numeric',
            'mch_id'=>'required|numeric',
            'key'=>'required',
            'app_secret'=>'required'
        ],[
            'app_id'=>'app_id不能为空',
            'mch_id'=>'mch_id不能为空',
            'key'=>'key不能为空',
            'app_secret'=>'app_secret不能为空'
        ]);
        return $handler->toResponse()->generateHttpResponse();
    }

    public function order(){
        return 1;
    }
}
