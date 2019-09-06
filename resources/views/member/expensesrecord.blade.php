@extends('member.layouts.admin_app')
@section('title')会员信息修改@stop
@section('head')
@stop
@section('content')
    <div class="col-md-10 box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">会员消费记录</h3>
        </div>
        <table class="table table-bordered">
            <tbody><tr>
                <th style="width: 70px">消费id</th>
                <th>消费详情</th>
                <th>积分消费比</th>
                <th style="width: 80px">剩余积分</th>
            </tr>
            <tr>
                <td>1.</td>
                <td>Update software</td>
                <td>
                    <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                    </div>
                </td>
                <td><span class="badge bg-red">55%</span></td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Clean database</td>
                <td>
                    <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                    </div>
                </td>
                <td><span class="badge bg-yellow">70%</span></td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Cron job running</td>
                <td>
                    <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                    </div>
                </td>
                <td><span class="badge bg-light-blue">30%</span></td>
            </tr>
            <tr>
                <td>4.</td>
                <td>Fix and squish bugs</td>
                <td>
                    <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div>
                </td>
                <td><span class="badge bg-green">90%</span></td>
            </tr>
            </tbody></table>

        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">«</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">»</a></li>
            </ul>
        </div>
    </div>
@stop
