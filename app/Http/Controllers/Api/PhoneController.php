<?php

namespace App\Http\Controllers\Api;

use App\AdminModel\Admin;
use App\AdminModel\Formid;
use App\AdminModel\Opendidstore;
use App\AdminModel\Phonemanage;
use App\Events\PhoneEvent;
use App\Notifications\MailSendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
class PhoneController extends Controller
{
    function phoneComplate(Request $request)
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ips=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
            $request['ip']=array_slice($ips,-1,1)[0];
        }else{
            $request['ip']=$request->getClientIp();
        }
        if(!empty($request->phoneno) && empty(Phonemanage::where('ip', $request['ip'])->where('created_at','>',Carbon::now()->subSeconds(50))->where('created_at','<',Carbon::now())->value('ip')) && empty(Phonemanage::where('phoneno', $request['phoneno'])->where('created_at','>',Carbon::now()->subDay())->where('created_at','<',Carbon::now())->value('phoneno')))
        {
            $request['host']=$request->input('host');
            $request['referer']='wxapplet';
            Phonemanage::create($request->all());
            Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
            //event(new PhoneEvent(Phonemanage::latest() ->first()));
            echo "提交成功，我们将尽快与您联系";
        }else{
            echo '电话号码已存在，请点击在线咨询客服';
        }
        //存储当前formid备用
        if (isset($request->formid) && !empty($request->formid))
        {
            Formid::create(['formid'=>$request->formid]);
        }
        //删除已存储的当前用户openid
        if (isset($request->openid) && !empty($request->openid))
        {
            Opendidstore::where('openid',$request->openid)->delete();
        }

    }
}
