<?php

namespace App\Console\Commands;

use App\AdminModel\Formid;
use App\AdminModel\Opendidstore;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Log;
class SendWechatNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wechat:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send wechat template notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sendMessage();
    }

    /**发送模板消息
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendMessage()
    {
        $storeopenids=Opendidstore::orderBy('id','asc')->pluck('openid','id');
        foreach ($storeopenids as $openidindex=>$storeopenid) {
            $form_id=Formid::where('created_at','>',Carbon::now()->subDays(6))->inRandomOrder()->first(['id','formid']);
            if ($form_id)
            {
                $client = new Client();
                $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".config('app.appid')."&secret=".config('app.secret');
                $access_token=json_decode($client->get($api,['verify' => false])->getBody()->getContents())->access_token;
                $data=[
                    "touser"=>$storeopenid, //接收用户的openid
                    "template_id"=>"m2k642HbG9jHGGXN0vbxEHrEzUP-bWPqVVn3W7lep-A",  //模板id
                    "page"=>'page/index/index',//点击模板消息跳转至小程序的页面
                    "form_id"=>$form_id->formid, //可为表单提交时form_id，也可是支付产生的prepay_id
                    "data"=>[
                        "keyword1"=>[
                            "value"=> "VaVa影像", //自定义参数
                            "color"=> '#173177'//自定义文字颜色
                        ],
                        "keyword2"=>[
                            "value"=> "宝宝照价目表领取",//自定义参数
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
                Log::info($form_id->formid);
                Log::info($response);
                Formid::where('id',$form_id->id)->delete();
                if (isset(json_decode($response)->errcode) && json_decode($response)->errcode==0)
                {
                    Opendidstore::where('id',$openidindex)->delete();
                }
                return $response;
            }
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
}
