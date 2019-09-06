@extends('admin.layouts.admin_app')
@section('title')前台会员编辑@stop
    @section('head')
    <style>td.newcolor span a{color: #ffffff; font-weight: 400; display: inline-block; padding: 2px;} td.newcolor span{margin-left: 5px;}</style>
@stop
@section('content')

    <div class="register-box">
        <div class="register-box-body">
            <p class="login-box-msg">前台用户编辑</p>
            {!! Form::model($User,array('action' =>array('Admin\FrontUserController@PostUserEdit', $User->id),'method' => 'put')) !!}
            <div class="form-group has-feedback">
                {{Form::text('name', null,array('class'=>'form-control','id'=>'name','readonly'=>'readonly','placeholder'=>'用户名'))}}
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {{Form::text('email', null,array('class'=>'form-control','id'=>'email','readonly'=>'readonly','placeholder'=>'登陆邮箱'))}}
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {{Form::password('password', array('class'=>'form-control','id'=>'password','placeholder'=>'密码'))}}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {{Form::password('password_confirmation', array('class'=>'form-control','id'=>'password_confirmation','placeholder'=>'确认密码'))}}
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>

            <div class="row">
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">提交</button>
                </div>
                <!-- /.col -->
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
        <!-- /.form-box -->
    </div>
    <!-- /.register-box -->
    <!-- /.row -->
    <!-- /.content -->
    @stop

