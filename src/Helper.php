<?php
/**
 * Created by PhpStorm.
 * User: bc-203
 * Date: 17-6-12
 * Time: 下午3:08
 */

namespace Notadd\Multipay;

class Helper
{
    public static function getSign($para)
    {
        ksort($para);//把参数按照首字母ASCII码从小到大排序

        //生成签名

        $str = urldecode(http_build_query($para))."&key=XM7gre777oHMbHOneNlopEhJCGbuPGYC";

        $sign = strtoupper(md5($str));

        return $sign;
    }

}