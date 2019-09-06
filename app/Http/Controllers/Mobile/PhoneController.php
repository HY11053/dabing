<?php

namespace App\Http\Controllers\Mobile;

use App\AdminModel\Admin;
use App\AdminModel\Phonemanage;
use App\Events\PhoneEvent;
use App\Notifications\MailSendNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhoneController extends Controller
{
    //开店成本计算器 侧边弹出
    function Complates(Request $request)
    {
        $phoneno=$request->input('phoneno');
        $jmfy=$request->input('jmfy');
        $dpzj=$request->input('dpzj');
        $rycb=$request->input('rycb');
        $mdjj=$request->input('mdjj');
        $mrcb=$request->input('mrcb');
        $cb=$dpzj+$rycb;
        $yye=$mdjj*$mrcb;
        $rlr=$mdjj*$mrcb*0.6;
        $referer=str_limit($request->session()->all()['referer'],100,'');
        if(empty(Phonemanage::where('phoneno',$phoneno)->value('phoneno')))
        {
            Phonemanage::create(['phoneno'=>$phoneno,'name'=>'计算器','ip'=>$request->getClientIp(),'note'=>'成本计算器提交','host'=>$request->input('host'),'referer'=>$referer]);
            event(new PhoneEvent(Phonemanage::latest() ->first()));
            Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
        }

        echo "
        <li class=''><span>加盟费：</span><strong id='materialPay'><em>$jmfy</em>元</strong></li>
									<li class=''><span>成本费：</span><strong id='artificialPay'><em>$cb</em>元</strong></li>
									<li class=''><span>营业额：</span><strong id='designPay'><em>$yye</em>元</strong></li>
									<li class=''><span>日利润：</span><strong id='qualityPay'><em>$rlr</em>元</strong></li>
        ";

    }
    function ComplateBrands(Request $request)
    {
        //开店成本计算，列表及内容页侧边
        $phoneno=$request->input('phoneno');
        $jmfy=$request->input('jmfy');
        $dpzj=$request->input('dpzj');
        $rycb=$request->input('rycb');
        $mdjj=$request->input('mdjj');
        $mrcb=$request->input('mrcb');
        $cb=$dpzj+$rycb;
        $yye=$mdjj*$mrcb;
        $rlr=$mdjj*$mrcb*0.6;
        $referer=str_limit($request->session()->all()['referer'],100,'');
        if(empty(Phonemanage::where('phoneno',$phoneno)->value('phoneno')))
        {
            Phonemanage::create(['phoneno'=>$phoneno,'name'=>'计算器','ip'=>$request->getClientIp(),'note'=>'成本计算器提交','host'=>$request->input('host'),'referer'=>$referer]);
            //event(new PhoneEvent(Phonemanage::latest() ->first()));
            //Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
        }
        echo "<div class=\"w260_result\" id=\"results\">
				<div class=\"w260_result_total\"><span class=\"w260_result_span\">您的开店预算</span><b id=\"bprice\">？</b><span>元</span></div>
				<div class=\"w260_list\">
					<ul class=\"w260_list_before\" id=\"replacecontent\">
						<li><span>加盟费：</span><em id=\"materialPay\">$jmfy</em>元</li>
						<li><span>成本费：</span><em id=\"artificialPay\">$cb</em>元</li>
						<li><span>营业额：</span><em id=\"designPay\">$yye</em>元</li>
						<li><span>日利润：</span><em id=\"qualityPay\">$rlr</em>元</li>
					</ul>
				</div>
				<p><a class=\"w260_btn\" href=\"javascript:void(0);\">返回重新计算</a></p>
			</div>";
    }


    function PhoneBottom(Request $request)
    {
        if(empty(Phonemanage::where('phoneno',$request->input('phoneno'))->value('phoneno')))
        {
            $request['ip']=$request->getClientIp();
            $request['host']=$request->input('host');
            $request['referer']=str_limit($request->session()->all()['referer'],100,'');
            Phonemanage::create($request->all());
            event(new PhoneEvent(Phonemanage::latest() ->first()));
            Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
            echo '电话提交成功！我们将尽快与您联系';
        }else{
            echo '电话号码已存在，请点击在线咨询客服';
        }
    }
}
