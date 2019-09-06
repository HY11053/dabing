@extends('admin.layouts.admin_app')
@section('title')抓取日志管理@stop
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
                    <h3 class="box-title">抓取日志管理 **数据更新时间为每日凌晨3点 {{$loginfos->total()}}</h3>
                    {{Form::open(array('route' => 'log_filter','files' => false,'class'=>'form-inline pull-right','method'=>'get'))}}
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-location-arrow" style="width:10px;"></i>
                            </div>
                            {{Form::select('mid', [1=>'pc',2=>'移动'], null,array('class'=>'form-control select2 pull-right','style'=>'width: 200px;','data-placeholder'=>"筛选域名",'multiple'=>"multiple"))}}
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">筛选数据</button>
                    {!! Form::close() !!}
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered table-striped  table-hover">
                        <tr>
                            <th>ip</th>
                            <th>抓取时间</th>
                            <th>请求方式</th>
                            <th>抓取地址</th>
                            <th>HTTP版本</th>
                            <th>状态码</th>
                            <th>页面大小</th>
                            <th>userAgent</th>
                            <th>referer来源</th>
                        </tr>
                        @if(isset($loginfos) && !empty($loginfos))
                            @foreach($loginfos as $loginfo)
                                <tr>
                                        @php
                                            $log=explode(' ',$loginfo->infos);
                                        @endphp
                                        <td>@if(isset($log[0])) {{$log[0]}} @endif</td>
                                        <td>@if(isset($log[3]) && isset($log[4])) {{$log[3]}}{{$log[4]}} @endif</td>
                                        <td>@if(isset($log[5])) {{trim($log[5],'"')}} @endif</td>
                                        <td>@if(isset($log[6])) {{$log[6]}} @endif</td>
                                        <td>@if(isset($log[7])) {{trim($log[7],'"')}} @endif</td>
                                        <td>@if(isset($log[8]) ) <span @if($log[8]!=200)  style="color: red; font-weight: bold;" @endif>{{$log[8]}}</span>@endif</td>
                                        <td>@if(isset($log[9])) {{$log[9]}} @endif</td>
                                        <td title="@if(isset($log[11])){{implode('',array_slice($log,11))}} @endif">@if(isset($log[11])){{str_limit(implode('',array_slice($log,11)),50,'...')}} @endif</td>
                                        <td title="@if(isset($log[10])){{$log[10]}} " @endif >@if(isset($log[10])){{str_limit($log[10],10,'...')}} @endif</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    {!! $loginfos->appends($arguments)->links() !!}
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
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
