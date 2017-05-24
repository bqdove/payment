<?php
/**
 * Created by PhpStorm.
 * User: ibenchu-024
 * Date: 2017/5/22
 * Time: 18:01
 */

namespace Notadd\Multipay;

use AopClient;
use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

class Alipay
{
  $alipay = new AopClient;
  $alipay->gatewayUrl = "https://openapi.alipay.com/gateway.do";
  $alipay->appId = "";
  $alipay->rsaPrivateKey = '' ;
  $alipay->format = "json";
  $alipay->charset= "GBK";
  $alipay->signType= "RSA2";
  $alipay->alipayrsaPublicKey = '';
  //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.open.public.template.message.industry.modify
  $request = new AlipayOpenPublicTemplateMessageIndustryModifyRequest();
  //SDK已经封装掉了公共参数，这里只需要传入业务参数
  //此次只是参数展示，未进行字符串转义，实际情况下请转义
  $request->bizContent = "{" +
  "    \"primary_industry_name\":\"IT科技/IT软件与服务\"," +
  "    \"primary_industry_code\":\"10001/20102\"," +
  "    \"secondary_industry_code\":\"10001/20102\"," +
  "    \"secondary_industry_name\":\"IT科技/IT软件与服务\"" +
  " }";
  $response= $alipay->execute($request);
}
