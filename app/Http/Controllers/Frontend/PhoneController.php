<?php

namespace App\Http\Controllers\Frontend;

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
        if(empty(Phonemanage::where('ip', $request->getClientIp())->where('created_at','>',Carbon::now()->subHour())->where('created_at','<',Carbon::now())->value('ip')))
        {
            Phonemanage::create(['phoneno'=>$phoneno,'name'=>'计算器','ip'=>$request->getClientIp(),'note'=>'成本计算器提交','host'=>$request->input('host'),'referer'=>$referer]);
            //event(new PhoneEvent(Phonemanage::latest() ->first()));
            Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
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

    /**通用页面电话提交
     * @param Request $request
     */
    function phoneComplate(Request $request)
    {
        if(empty(Phonemanage::where('ip', $request->getClientIp())->where('created_at','>',Carbon::now()->subHour())->where('created_at','<',Carbon::now())->value('ip')))
        {
            $request['host']=$request->input('host');
            $request['ip']=$request->getClientIp();
            $request['referer']=str_limit($request->session()->all()['referer'],100,'');
            Phonemanage::create($request->all());
            Admin::first()->notify(new MailSendNotification(Phonemanage::latest() ->first()));
            //event(new PhoneEvent(Phonemanage::latest() ->first()));
            echo '电话提交成功！我们将尽快与您联系';
        }else{
            echo '电话号码已存在，请点击在线咨询客服';
        }
    }
}
