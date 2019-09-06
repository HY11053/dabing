@extends('member.layouts.admin_app')
@section('title')会员密码修改@stop
@section('head')
@stop
@section('content')
    <div class="col-md-10 box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">会员密码修改:{{$thisuserinfo->email}}</h3>
        </div>
        <section class="content">
            <div class="row" >
                <div class="col-md-4 " style="background: #fff;">
                    {{Form::model($thisuserinfo,array('route' =>array( 'repassword','id'=>$thisuserinfo->id),'method' => 'put','files' => true,))}}
                    <div class="box-body">
                        <div class="form-group">
                            {{Form::label('password','旧密码')}}
                            {{Form::password('password',array('class'=>'form-control','id'=>'password','placeholder'=>'旧密码'))}}
                        </div>
                        <div class="form-group">
                            {{Form::label('newpassword','新密码')}}
                            {{Form::password('newpassword',array('class'=>'form-control','id'=>'newpassword','placeholder'=>'新密码'))}}
                        </div>
                        <div class="form-group">
                            {{Form::label('newpassword-confirm','确认密码')}}
                            {{Form::password('newpassword',array('class'=>'form-control','id'=>'newpassword-confirm','placeholder'=>'确认密码'))}}
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
