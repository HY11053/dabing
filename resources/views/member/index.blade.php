@extends('member.layouts.admin_app')
@section('title')世纪母婴网会员中心@stop
@section('head')
@stop
@section('content')
    <div class="col-md-10 box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">当前会员基础信息</h3>
        </div>
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-solid">
                    <div class="box-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#"><i class="fa fa-inbox"></i> 会员名称<span class="pull-right" style="margin-top: 0px;">{{$thisuserinfo->name}}</span></a></li>
                            <li><a href="#"><i class="fa fa-inbox"></i> 登录账号<span class="pull-right" style="margin-top: 0px;">{{$thisuserinfo->email}}</span></a></li>
                            <li><a href="#"><i class="fa fa-envelope-o"></i> 登陆时间：<span class="pull-right" style="margin-top: 0px;">{{Carbon\Carbon::now()}}</span></a></li>
                            <li><a href="#"><i class="fa fa-file-text-o"></i> 登录IP地址：<span class="pull-right" style="margin-top: 0px;">{{Request::getClientIp()}}</span></a></li>
                            <li><a href="#"><i class="fa fa-filter"></i> 剩余积分 <span class="label label-warning pull-right" style="margin-top: 0px;">{{\App\User::where('id',auth('web')->user()->id)->value('total_score')}}</span></a></li>
                            <li><a href="#"><i class="fa fa-trash-o"></i> 公司名称：<span class="pull-right" style="margin-top: 0px;">{{$thisuserinfo->group}}</span></a></li>
                            <li><a href="#"><i class="fa fa-trash-o"></i> 合作品牌：<span class="pull-right" style="margin-top: 0px;">{{$thisuserinfo->brandname}}</span></a></li>
                            <li><a href="#"><i class="fa fa-trash-o"></i> 联系电话：<span class="pull-right" style="margin-top: 0px;">{{$thisuserinfo->mobilephone}}</span></a></li>
                            <li><a href="#"><i class="fa fa-trash-o"></i> 联系地址：<span class="pull-right" style="margin-top: 0px;">{{$thisuserinfo->address}}</span></a></li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        </section>
    </div>
@stop
