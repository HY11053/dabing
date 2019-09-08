@extends('admin.layouts.admin_app')
@section('title')小程序首页模板列表@stop
@section('head')
    <style>.red{color: red;}</style>
@stop
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">列表管理 文档总计{{$fixedtemplatelists->total()}}</h3>
                    <div class="box-tools">
                        <div class="pull-right" style="display:inline-block; width: 210px">
                            <a href="{{action('Admin\WechatFixedtemplateController@FixedtemplateCreate')}}" style="color: #ffffff ; display: inline-block; padding-left: 3px;"><button  class="btn btn-sm btn-default bg-blue"><i class="fa  fa-pencil-square" style="padding-right: 3px;"></i>小程序固定模板添加</button></a>
                        </div>
                        <form action="/admin/search" method="post" class="form-group pull-right col-md-2 col-xs-6">
                            <div class="input-group input-group-sm ">
                                <input type="text" name="title" class="form-control pull-right" placeholder="普通文档搜索">
                                {{csrf_field()}}
                                <div class="input-group-btn"><button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button></div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>ID</th>
                            <th>小程序标题</th>
                            <th>小程序别名</th>
                            <th>发布时间</th>
                            <th>发布人</th>
                            <th>操作</th>
                        </tr>
                        @foreach($fixedtemplatelists as $fixedtemplatelist)
                            <tr>
                                <td>{{$fixedtemplatelist->id}}</td>
                                <td>{{$fixedtemplatelist->title}}</td>
                                <td>{{$fixedtemplatelist->shorttitle}}</td>
                                <td>@if(\Carbon\Carbon::now() > \Carbon\Carbon::parse($fixedtemplatelist->created_at)->addDays(7)){{$fixedtemplatelist->created_at}} @else{{\Carbon\Carbon::parse($fixedtemplatelist->created_at)->diffForHumans()}}@endif </td>
                                <td>{{$fixedtemplatelist->editor}}</td>
                                <td class="astyle">
                                   <span class="label label-warning"><a href="/admin/wxapplet/fixedtemplateedit/{{$fixedtemplatelist->id}}">编辑</a></span>
                                    <span class="label label-danger"><a data-toggle="modal" data-target=".modal-sm{{$fixedtemplatelist->id}}" >删除</a></span>
                                    <div class="modal fade modal-sm{{$fixedtemplatelist->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel{{$fixedtemplatelist->id}}">
                                        <div class="modal-dialog modal-sm modal-s-m{{$fixedtemplatelist->id}}" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <h5 class="modal-title" id="mySmallModalLabel{{$fixedtemplatelist->id}}">是否要删除当前文章</h5>
                                                </div>
                                                <div class="modal-body">
                                                    {{$fixedtemplatelist->title}}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
                                                    <button type="button" class="btn btn-primary" id="btn-{{$fixedtemplatelist->id}}" onclick="AjDelete({{$fixedtemplatelist->id}},'modal-s-m{{$fixedtemplatelist->id}}')">删除</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                    {!! $fixedtemplatelists->links() !!}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

    <!-- /.content -->
@stop
@section('libs')
    <script>
        function startRequest() {
            window.location.reload()
        }
        function AjDelete (id,node) {
            var id = id;
            var node=node;
            $.ajax({
                //提交数据的类型 POST GET
                type:"POST",
                //提交的网址
                url:"/admin/wxapplet/fixedtemplatedelete/"+id,
                //提交的数据
                data:{"id":id,'node':node},
                //返回数据的格式
                datatype: "html",    //"xml", "html", "script", "json", "jsonp", "text".
                success:function (response, stutas, xhr) {
                    $(".modal-s-m"+id+" .modal-body").html(response+'请稍后 跳转中');
                    $("#btn-"+id).attr("disabled","disabled");
                    setInterval("startRequest()", 6000);
                }
            });
        }
    </script>
@stop


