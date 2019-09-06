<?php

namespace App\Http\Controllers\Api;

use App\AdminModel\Admin;
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
        if(empty(Phonemanage::where('ip', $request['ip'])->where('created_at','>',Carbon::now()->subHour())->where('created_at','<',Carbon::now())->value('ip')))
        {
            $request['host']=$request->input('host');
            $request['referer']='baiduapplet';
            Phonemanage::create($request->all());
            Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
            //event(new PhoneEvent(Phonemanage::latest() ->first()));
            echo "提交成功，我们将尽快与您联系";
        }else{
            echo '电话号码已存在，请点击在线咨询客服';
        }
    }

    function phoneMipComplate(Request $request)
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ips=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
            $request['ip']=array_slice($ips,-1,1)[0];
        }else{
            $request['ip']=$request->getClientIp();
        }
        $request['host']=$request->input('host');
        if(empty(Phonemanage::where('ip', $request['ip'])->where('created_at','>',Carbon::now()->subHour())->where('created_at','<',Carbon::now())->value('ip')))
        {
            $request['referer']='mip1';
            Phonemanage::create($request->all());
            Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
            //event(new PhoneEvent(Phonemanage::latest() ->first()));
            $info= '<script> alert("提交成功，我们将尽快与您联系");
                        window.location="'.str_replace(['?ref=buttom','?ref=tanchuang'],'',$request['host']).'";
                        </script>';
            echo $info;
        }else{
            $info= '<script> alert("电话号码已存在，我们将尽快与您联系");
                        window.location="'.str_replace(['?ref=buttom','?ref=tanchuang'],'',$request['host']).'";
                        </script>';
            echo $info;
        }
    }

    function phoneMipComplate2(Request $request)
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ips=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
            $request['ip']=array_slice($ips,-1,1)[0];
        }else{
            $request['ip']=$request->getClientIp();
        }
        $request['host']=$request->input('host2');
        if(empty(Phonemanage::where('ip', $request['ip'])->where('created_at','>',Carbon::now()->subHour())->where('created_at','<',Carbon::now())->value('ip')))
        {
            $request['name']=$request->input('name2');
            $request['phoneno']=$request->input('phoneno2');
            $request['referer']='mip2';
            Phonemanage::create($request->all());
            Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
            //event(new PhoneEvent(Phonemanage::latest() ->first()));
            $info= '<script> alert("提交成功，我们将尽快与您联系");
                        window.location="'.str_replace(['?ref=buttom','?ref=tanchuang'],'',$request['host']).'";
                        </script>';
            echo $info;
        }else{
            $info= '<script> alert("电话号码已存在，我们将尽快与您联系");
                        window.location="'.str_replace(['?ref=buttom','?ref=tanchuang'],'',$request['host']).'";
                        </script>';
            echo $info;
        }
    }
}
