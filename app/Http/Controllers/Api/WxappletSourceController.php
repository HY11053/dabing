<?php

namespace App\Http\Controllers\Api;

use App\AdminModel\Formid;
use App\AdminModel\Wchatappletindex;
use App\AdminModel\Wechatsigntemplet;
use App\WechatRes\WxUtils;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Log;

class WxappletSourceController extends Controller
{
    /**固定模板小程序内容获取
     * @param Request $request
     * @return string
     */
    public function geiIndexfixedtemplate(Request $request)
    {
        $indexInfos= Wchatappletindex::when($request->id, function ($query) use ($request) {
            return $query->where('id',$request->id);
        }, function ($query) {
            return $query->orderBy('id','asc');
        })->when($request->random, function ($query){
            return $query->inRandomOrder();
        })->first(['title','shorttitle','navtitle1','navtitle2','navtitle3','imagepics','navpics','buttonone','longpics','buttontwo','longtwopics']);
        if (!empty($indexInfos))
        {
            $indexInfos=$indexInfos->toArray();
            $indexInfos['imagepics']=$this->processImgPath($indexInfos['imagepics']);
            $indexInfos['navpics']=$this->processImgPath($indexInfos['navpics']);
            $indexInfos['longpics']=$this->processImgPath($indexInfos['longpics']);
            $indexInfos['longtwopics']=$this->processImgPath($indexInfos['longtwopics']);
            return !empty($indexInfos)?json_encode($indexInfos):'';
        }
        return $indexInfos;
    }

    /**独立模板页面获取
     * @param Request $request
     * @return string
     */
    public function getSignTemplate(Request $request)
    {
        $thisarticleinfos= Wechatsigntemplet::when($request->id, function ($query) use ($request) {
            return $query->where('id',$request->id);
        }, function ($query) {
            return $query->orderBy('id','asc');
        })->first(['title','shorttitle','litpic']);
        if (!empty($thisarticleinfos))
        {
            $thisarticleinfos=$thisarticleinfos->toArray();
            $thisarticleinfos['litpic']=config('app.url').str_replace(config('app.url'),'',$thisarticleinfos['litpic']);
            return !empty($thisarticleinfos)?json_encode($thisarticleinfos):'';
        }
        return $thisarticleinfos;
    }

    /**获取微信appid和sessionkey
     * @param Request $request
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getOpenid(Request $request){
        $client = new Client();
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$request->appid}&secret={$request->secret}&js_code={$request->code}&grant_type=authorization_code";
        $openid = $client->get($api,['verify' => false])->getBody();
        return $openid;
    }

    /**解密手机号码
     * @param Request $request
     * @return int
     */
    public function getPhoneNumber(Request $request)
    {
        //这是解密手机号码的接口，等会前端还要写个请求访问这个接口拿到手机号码
		$appid = $request['appid'];
        $session_key = $request['session_key'];
        $encryptedData = $request['encryptedData'];
        $iv = $request['iv'];
        $pc =new \WXBizDataCrypt($appid, $session_key);;
		$errCode = $pc->decryptData($encryptedData, $iv, $data );
		if ($errCode == 0) {
            return $data;
        } else {
            return $errCode;
        }
	}

    /**发送模板消息
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
	public function sendMessage(Request $request)
    {
        $form_id=Formid::where('created_at','>',Carbon::now()->subDays(6))->inRandomOrder()->first(['id','formid']);
        $openid=$request->get("openid");

        $client = new Client();
        $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".config('app.appid')."&secret=".config('app.secret');
        $access_token=json_decode($client->get($api,['verify' => false])->getBody()->getContents())->access_token;
        if ($form_id)
        {
            $data=[
                "touser"=>$openid, //接收用户的openid
                "template_id"=>"m2k642HbG9jHGGXN0vbxEHrEzUP-bWPqVVn3W7lep-A",  //模板id
                "page"=>"pages/index/index",//点击模板消息跳转至小程序的页面
                "form_id"=>$form_id->formid, //可为表单提交时form_id，也可是支付产生的prepay_id
                "data"=>[
                    "keyword1"=>[
                        "value"=> "VaVa影像", //自定义参数
                        "color"=> '#173177'//自定义文字颜色
                    ],
                    "keyword2"=>[
                        "value"=> "摄影优惠价目表领取",//自定义参数
                        "color"=> '#173177'//自定义文字颜色
                    ],
                    "keyword5"=>[
                        "value"=> "请至小程序订单列表进行查看",//自定义参数
                        "color"=> '#173177'//自定义文字颜色
                    ],
                ]
            ];
            $tempapi="https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token;
            $response=$this->https_request($tempapi,json_encode($data));
            Log::info($response);
            Formid::where('id',$form_id->id)->delete();
            return $response;
        }

    }

    /**curl 请求发送模板消息
     * @param $url
     * @param null $data
     * @return mixed
     */
    private function https_request($url, $data=null) {
        //创建一个新cURL资源
        $curl = curl_init();
        //设置URL和相应的项
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //抓取URL并把它传递给浏览器
        $output = curl_exec($curl);
        // 关闭cURL资源，并且释放系统资源
        curl_close($curl);
        return $output;
    }

    /**图片路径处理
     * @param $path
     * @return 0|array
     */
    private function processImgPath($path)
    {
        if (!empty($path))
        {
            $pics=array_slice(array_filter(explode(",",$path)),0,3);
            //图集路径处理
            foreach ($pics as $index=>$pic)
            {
                if (!str_contains($pic,'://'))
                {
                    $pics[$index]=config('app.url').$pic;
                }
            }
            return $pics;
        }

    }
}
