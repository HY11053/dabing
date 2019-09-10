@extends('admin.layouts.admin_app')
@section('title')小程序Formid列表@stop
@section('head')
    <style>.red{color: red;}</style>
@stop
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">列表管理 小程序Formid总计{{$idsources->total()}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>ID</th>
                            <th>FormId</th>
                            <th>创建时间</th>
                        </tr>
                        @foreach($idsources as $idsource)
                            <tr>
                                <td>{{$idsource->id}}</td>
                                <td>{{$idsource->formid}}</td>
                                <td>@if(\Carbon\Carbon::now() > \Carbon\Carbon::parse($idsource->created_at)->addDays(7)){{$idsource->published_at}} @else{{\Carbon\Carbon::parse($idsource->created_at)->diffForHumans()}}@endif </td>
                            </tr>
                        @endforeach

                    </table>
                    {!! $idsources->links() !!}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <!-- /.content -->
@stop


