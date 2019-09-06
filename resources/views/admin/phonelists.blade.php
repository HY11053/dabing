@extends('admin.layouts.admin_app')
@section('title')电话提交列表@stop
@section('head')
<style>td.newcolor span a{color: #ffffff; font-weight: 400; display: inline-block; padding: 2px;} td.newcolor span{margin-left: 5px;}</style>
<link rel="stylesheet" href="/adminlte/plugins/datepicker/datepicker3.css">
<link href="/adminlte/plugins/select2/select2.min.css" rel="stylesheet">
@stop
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">电话提交列表{{$phoneNos->total()}}</h3>
                    {{Form::open(array('route' => 'phone_filter','files' => false,'class'=>'form-inline pull-right','method'=>'get'))}}
                    <div class="form-group">
                        <div class="input-group date " >
                            <div class="input-group-addon">
                                <i class="fa fa-calendar" style="width:10px;"></i>
                            </div>
                            {{Form::text('start_at', null, array('class' => 'form-control pull-right','id'=>'datepicker','placeholder'=>'开始时间','autocomplete'=>"off",'style'=>'width:100%'))}}
                        </div>
                    </div>
                    <div class="input-group date " >
                        <div class="input-group-addon">
                            <i class="fa fa-calendar" style="width:10px;"></i>
                        </div>
                        {{Form::text('end_at', null, array('class' => 'form-control pull-right','id'=>'datepicker1','placeholder'=>'结束时间','autocomplete'=>"off",'style'=>'width:100%'))}}
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-location-arrow" style="width:10px;"></i>
                            </div>
                            {{Form::select('advertisement', ['jjedu.com.cn'=>'jjedu.com.cn'], null,array('class'=>'form-control select2 pull-right','style'=>'width: 200px;','data-placeholder'=>"筛选域名",'multiple'=>"multiple"))}}
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">筛选数据</button>
                    {!! Form::close() !!}
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered table-striped  table-hover">
                        <tr>
                            <th style="width: 10px">#ID</th>
                            <th>姓名</th>
                            <th>电话</th>
                            <th>备注</th>
                            <th>提交页面</th>
                            <th>品牌分类</th>
                            <th>来源</th>
                            <th>IP</th>
                            <th>归属地</th>
                            <th>提交时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($phoneNos as $adminlist)
                        <tr>
                            <td>{{$adminlist->id}}.</td>
                            <td>{{$adminlist->name}}</td>
                            {{--{{substr_replace(decrypt($adminlist->phoneno),'***',3,3)}}--}}
                            @if(auth('admin')->id()==1)
                            <td>{{decrypt($adminlist->phoneno)}}</td>
                            @else
                            <td>{{substr_replace(decrypt($adminlist->phoneno),'***',3,3)}}</td>
                            @endif
                            <td>{{str_limit($adminlist->note,10,'')}}</td>
                           <td>{{str_limit($adminlist->host,73,'')}}</td>
                           <td>
                               @php
                                   preg_match('#\/news\/(\d+)\.shtml#',$adminlist->host,$matches);
                                   if (isset($matches[1]))
                                   {
                                   $thisartcileinfos=\App\AdminModel\Archive::where('id',$matches[1])->first(['id','brandid','created_at','typeid']);
                                   $thisbrandartcileinfos=\App\AdminModel\Brandarticle::where('id',$thisartcileinfos->brandid)->first(['id','brandname','typeid']);
                                   if ($thisartcileinfos->brandid && isset($thisbrandartcileinfos->brandname))
                                   {
                                    $brand=$thisbrandartcileinfos->brandname;
                                    $type=$thisbrandartcileinfos->arctype->typename;
                                   }elseif($thisartcileinfos->bdname){
                                    $brand=$thisartcileinfos->bdname;
                                    $type='暂未分类';
                                   }else{
                                   $brand='暂未分类';
                                    $type='暂未分类';
                                   }
                                   $isnew=\App\AdminModel\Archive::where('id',$matches[1])->value('created_at')>\Carbon\Carbon::now()->subMonth()?'<i class="fa  fa-fire"></i>':'';
                                   }else{
                                       preg_match('#\/xm\/(\d+)\.shtml#',$adminlist->host,$matches);
                                       if (isset($matches[1])){
                                           $brand=\App\AdminModel\Brandarticle::where('id',$matches[1])->value('brandname');
                                           if(empty($brand))
                                           {
                                           $brand='品牌已删除';
                                            $type='';
                                            $isnew='';
                                           }else{
                                           $brandarticle=\App\AdminModel\Brandarticle::where('id',$matches[1])->first();
                                           $isnew=\App\AdminModel\Brandarticle::where('id',$matches[1])->value('created_at')>\Carbon\Carbon::now()->subMonth()?'<i class="fa  fa-fire"></i>':'';
                                           $type=$brandarticle->arctype->typename;
                                           }
                                       }else{
                                       $brand='暂未分类';
                                       $type='';
                                       $isnew='';
                                       }
                                   }
                               echo '<span style="color:red;">'.$isnew.'</span>'.$brand.'——<strong style="color:red;">'.$type.'</strong>';
                               @endphp
                           </td>
                           <td title="{{$adminlist->referer}}">
                           @if(stristr($adminlist->referer,'baidu'))
                               百度
                               @elseif(stristr($adminlist->referer,'so.com'))
                               360
                               @elseif(stristr($adminlist->referer,'sogou'))
                                搜狗
                               @elseif(stristr($adminlist->referer,'sm.cn'))
                               神马
                               @else
                                其他
                               @endif
                           </td>
                           <td>{{$adminlist->ip}}</td>
                            <td>@if($adminlist->ip) @foreach(\Zhuzhichao\IpLocationZh\Ip::find(trim($adminlist->ip)) as $index=>$area) @if($index<3){{$area}}-@endif @endforeach @endif</td>
                            <td>{{$adminlist->created_at}}</td>
                            <td class="newcolor"><span class="badge bg-green"><a href="/admin/phone/edit/{{$adminlist->id}}">编辑</a></span> <span class="badge bg-red"><a href="/admin/phone/delete/{{$adminlist->id}}">删除</a> </span></td>
                        </tr>
                       @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    {!! $phoneNos->appends($arguments)->links() !!}
                </div>
            </div>
            <!-- /.box -->
        </div>

    </div>
    <!-- /.row -->
    <!-- /.content -->
@stop
@section('libs')
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="/adminlte/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js"></script>
    <script src="/adminlte/plugins/select2/select2.full.min.js"></script>
    <script>
        $('.select2').select2();
        $(function () {
            $('#datepicker,#datepicker1').datepicker({
                autoclose: true,
                language: 'zh-CN',
                todayHighlight: true
            });
        });
    </script>
@stop
