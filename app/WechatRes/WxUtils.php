<?php
/**
 * Created by PhpStorm.
 * User: zhoudi
 * Date: 2018/10/3
 * Time: 下午6:27
 */

namespace App\WechatRes;


use Illuminate\Support\Facades\Cache;

class WxUtils
{
    const appid = "wx76ada57c62703fff"; //你的appid
    const secret = "9ac061abb49c7904fb2dedd4a20be57b";//你的secret

    public static function access_token(){
        $Url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appid."&secret=".self::secret;
        $access_token=Cache::get("access_token");
        if($access_token==""){
            $access_token=json_decode(self::curl($Url))->{"access_token"};
            Cache::put("access_token",$access_token,120);
        }
        return $access_token;
    }
    public static function SendMsg($data,$access_token){
        $MsgUrl="https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token;
        return json_decode(self::curl($MsgUrl,$params=json_encode($data),$ispost=1,$https=1));
    }
    public static function curl($url, $params = false, $ispost = 0, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8'
            )
        );
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}