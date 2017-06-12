<?php
/**
 * Created by PhpStorm.
 * User: bc021
 * Date: 17-6-9
 * Time: 下午11:42
 */

namespace Notadd\Multipay;

class Helper
{
    public static function getSign($para)
    {
        ksort($para);//把参数按照首字母ASCII码从小到大排序


        $newParaArray = [];

        $stringSignTemp = '';
        //以下两个foreach循环的目的是把参数序列化为a=1&b=2&c=3的格式
        foreach($para as $key => $value)
        {
            if ($value == ''){
                continue;
            }
            array_push($newParaArray,$key."=".$value);
        }

        foreach($newParaArray as $key => $newPara)
        {
            if ($key == 0)
            {
                $stringSignTemp .= $newPara;
            }else{
                $stringSignTemp .= '&'. $newPara;
            }
        }
        //格式化好后链接商户平台的key值
        $stringSignTemp .= "&key=XM7gre777oHMbHOneNlopEhJCGbuPGYC";

        //最后MD5加密并转化为大写,$sign 存储签名
        $sign=strtoupper(MD5($stringSignTemp));

        return $sign;
    }
}