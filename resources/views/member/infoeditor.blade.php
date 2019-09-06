@extends('member.layouts.admin_app')
@section('title')会员信息修改@stop
@section('head')
@stop
@section('content')
    <div class="col-md-10 box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">会员基础信息修改</h3>
        </div>
    <section class="content">
        <div class="row" >
            <div class="col-md-4 " style="background: #fff;">
                {{Form::model($thisuserinfo,array('route' =>array( 'userinfo_edit','id'=>$thisuserinfo->id),'method' => 'put','files' => true,))}}
                <div class="box-body">
                    <div class="form-group">
                        {{Form::label('name','会员名称')}}
                        {{Form::text('name', null,array('class'=>'form-control','id'=>'name','placeholder'=>'会员名称'))}}
                    </div>
                    <div class="form-group">
                        {{Form::label('group','公司名称')}}
                        {{Form::text('group', null,array('class'=>'form-control','id'=>'group','placeholder'=>'公司名称'))}}
                    </div>
                    <div class="form-group">
                        {{Form::label('mobilephone','联系电话')}}
                        {{Form::text('mobilephone', null,array('class'=>'form-control','id'=>'mobilephone','placeholder'=>'联系电话'))}}
                    </div>
                    <div class="form-group">
                        {{Form::label('address','联系地址')}}
                        {{Form::text('address', null,array('class'=>'form-control','id'=>'address','placeholder'=>'联系地址'))}}
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">提交修改</button>
                    </div>
                </div>
                {!! Form::close() !!}
                @if(count($errors) > 0)
                    <ul class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    </div>
@stop
