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
use Notadd\Multipay\Handlers\WechatWebNotifyHandler;

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
            'app_id'=>'required|regex:/\w{18}/',
            'mch_id'=>'required|regex:/\d{10}/',
            'key'=>'required|regex:/\w{32}/',
            'app_secret'=>'required|regex:/\w/',
            'cert'=>'required',
            'cert_key'=>'required'
        ],[
            'app_id.required'=>'app_id不能为空',
            'app_id.regex'=>'appid必须为18位字符串',
            'mch_id.required'=>'商户Id不能为空',
            'mch_id.regex'=>'商户Id必须为为10位数字',
            'key.required'=>'商户密钥不能为空',
            'key.regex'=>'商户密钥必须为32位字符串',
            'app_secret.regex'=>'app_secret必须为字符串格式',
            'app_secret.required'=>'app_secret不能为空',
            'cert.required'=>'证书不能为空',
//            'cert.mimes'=>'证书必须为pem格式的',
            'cert_key.required'=>'证书不能为空',
//            'cert_key.mimes'=>'证书必须为pem格式'
        ]);

        return $handler->toResponse()->generateHttpResponse();
    }

    //回调通知

    public function webNotify(WechatWebNotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }




}
