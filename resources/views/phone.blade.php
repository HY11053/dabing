<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 |     网站文档列表
    </title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="G3qiZra3L7vO0WQIw14ktBPEVkeRd7FQ9AMCqsqC">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/AdminLTE/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/AdminLTE/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/AdminLTE/dist/css/skins/overwrite.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="row">
    <div class="col-md-10 box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">测试数据</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(array('action' => 'Admin\PhoneManageController@CreatePhoneManage','role'=>'form')) !!}
        <div class="box-body">
            <div class="form-group">
                {{Form::label('name','姓名')}}
                {{Form::text('name', null,array('class'=>'form-control','id'=>'url','placeholder'=>'姓名'))}}
            </div>
            <div class="form-group">
                {{Form::label('phoneno','电话')}}
                {{Form::text('phoneno', null,array('class'=>'form-control','id'=>'webname','placeholder'=>'电话'))}}
            </div>
            <div class="form-group">
                {{Form::label('address','联系方式')}}
                {{Form::text('address', null,array('class'=>'form-control','id'=>'address','placeholder'=>'联系方式'))}}
            </div>
            <div class="form-group">
                {{Form::label('note','操作备注')}}
                {{Form::text('note', null,array('class'=>'form-control','id'=>'note','placeholder'=>'操作备注'))}}
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
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

</div>
</body>
</html>