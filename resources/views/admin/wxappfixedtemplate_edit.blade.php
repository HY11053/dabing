@extends('admin.layouts.admin_app')
@section('title')小程序首页模板内容修改@stop
@section('head')
    <link href="/adminlte/plugins/bootstrap-fileinput/css/fileinput.min.css" rel="stylesheet">
@stop
@section('content')
    <!-- row -->
    <div class="row">
        {{Form::model($thisfixedtemplate,array('route' =>array( 'fixedtemplate_update','id'=>$thisfixedtemplate->id),'method' => 'put','files' => true,))}}
        <div class="col-md-12">
            <!-- The time line -->
            <ul class="timeline">
                <!-- timeline time label -->
                <li class="time-label">
                  <span class="bg-red">
                     {{date("M j, Y")}}
                  </span>
                </li>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <li>
                    <i class="fa fa-pencil-square bg-blue"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{date('H:i')}}</span>

                        <h3 class="timeline-header"><a href="#">小程序首页基本信息|</a> 按需填写</h3>

                        <div class="timeline-body basic_info">
                            <div class="form-group col-md-12">
                                {{Form::label('title', '小程序广告名称', array('class' => 'control-label col-md-2'))}}
                                <div class="col-md-4">
                                    {{Form::text('title', null, array('class' => 'form-control','id'=>'title','placeholder'=>'小程序首页名称'))}}
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                {{Form::label('shorttitle', '小程序广告别名', array('class' => 'control-label col-md-2'))}}
                                <div class="col-md-4">
                                    {{Form::text('shorttitle', null, array('class' => 'form-control','id'=>'shorttitle','placeholder'=>'小程序广告别名'))}}
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                {{Form::label('buttonone', '按钮1名称', array('class' => 'control-label col-md-2 col-sm-3 col-xs-12'))}}
                                <div class="col-md-4 col-sm-9 col-xs-12">
                                    {{Form::text('buttonone', null, array('class' => 'form-control','id'=>'buttonone','placeholder'=>'按钮1名称'))}}
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                {{Form::label('buttontwo', '按钮2名称', array('class' => 'control-label col-md-2 col-sm-3 col-xs-12'))}}
                                <div class="col-md-4 col-sm-9 col-xs-12">
                                    {{Form::text('buttontwo',null, array('class' => 'form-control','id'=>'buttontwo','placeholder'=>'按钮2名称'))}}
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                {{Form::label('navtitle1', '导航小图文字1', array('class' => 'control-label col-md-2 col-sm-3 col-xs-12'))}}
                                <div class="col-md-4 col-sm-9 col-xs-12">
                                    {{Form::text('navtitle1',null, array('class' => 'form-control','id'=>'navtitle1','placeholder'=>'导航小图文字1'))}}
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                {{Form::label('navtitle2', '导航小图文字2', array('class' => 'control-label col-md-2 col-sm-3 col-xs-12'))}}
                                <div class="col-md-4 col-sm-9 col-xs-12">
                                    {{Form::text('navtitle2',null, array('class' => 'form-control','id'=>'navtitle2','placeholder'=>'导航小图文字2'))}}
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                {{Form::label('navtitle3', '导航小图文字3', array('class' => 'control-label col-md-2 col-sm-3 col-xs-12'))}}
                                <div class="col-md-4 col-sm-9 col-xs-12">
                                    {{Form::text('navtitle3',null, array('class' => 'form-control','id'=>'navtitle3','placeholder'=>'导航小图文字3'))}}
                                </div>
                            </div>
                        </div>
                        <div class="timeline-footer">
                            <button class="btn btn-primary btn-xs" disabled="disabled"></button>
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <li>
                    <i class="fa fa-camera bg-green"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{date('j, n,y')}}</span>
                        <h3 class="timeline-header"><a href="#">首页幻灯大图</a> 批量上传图集</h3>
                        <div class="timeline-body">
                            {{Form::file('image', array('name'=>'input-image','class' => 'file-loading','id'=>'input-image-1','hiddenfield'=>'imagepics', 'multiple','accept'=>'image/*'))}}
                            <div id="kv-success-modal-imagepics" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">图片上传成功</h4>
                                        </div>
                                        <div id="kv-success-box-imagepics" class="modal-body">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{Form::hidden('imagepics', null,array('id'=>'imagepics'))}}
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->
                <li>
                    <i class="fa fa-camera bg-aqua"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{date('j, n,y')}}</span>
                        <h3 class="timeline-header"><a href="#">首页下方三小图</a> 批量上传图集</h3>
                        <div class="timeline-body">
                            {{Form::file('image', array('name'=>'input-image','class' => 'file-loading','id'=>'input-image-2','hiddenfield'=>'navpics', 'multiple','accept'=>'image/*'))}}
                            <div id="kv-success-modal-navpics" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">图片上传成功</h4>
                                        </div>
                                        <div id="kv-success-box-navpics" class="modal-body">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{Form::hidden('navpics', null,array('id'=>'navpics'))}}
                        </div>
                    </div>
                </li>
                <li>
                    <i class="fa fa-camera bg-blue"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{date('j, n,y')}}</span>
                        <h3 class="timeline-header"><a href="#">三小图下方图集</a> 批量上传图集</h3>
                        <div class="timeline-body">
                            {{Form::file('image', array('name'=>'input-image','class' => 'file-loading','id'=>'input-image-3','hiddenfield'=>'longpics','multiple','accept'=>'image/*'))}}
                            <div id="kv-success-modal-longpics" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">图片上传成功</h4>
                                        </div>
                                        <div id="kv-success-box-longpics" class="modal-body">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{Form::hidden('longpics', null,array('id'=>'longpics'))}}
                        </div>
                    </div>
                </li>
                <li>
                    <i class="fa fa-camera bg-red"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{date('j, n,y')}}</span>
                        <h3 class="timeline-header"><a href="#">按钮2下方图集</a> 批量上传图集</h3>
                        <div class="timeline-body">
                            {{Form::file('image', array('name'=>'input-image','class' => 'file-loading','id'=>'input-image-4','hiddenfield'=>'longtwopics','multiple','accept'=>'image/*'))}}
                            <div id="kv-success-modal-longtwopics" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">图片上传成功</h4>
                                        </div>
                                        <div id="kv-success-box-longtwopics" class="modal-body">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{Form::hidden('longtwopics', null,array('id'=>'longtwopics'))}}
                        </div>
                        <div class="timeline-footer">
                            <button type="submit"  class="btn btn-md bg-maroon">提交文档</button>
                        </div>
                    </div>
                </li>
                <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                </li>
            </ul>
        </div>
        <!-- /.col -->
        {!! Form::close() !!}
    </div>
@stop

@section('libs')
    <!-- iCheck -->
    <script src="/adminlte/plugins/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/adminlte/plugins/bootstrap-fileinput/js/locales/zh.js"></script>
    <script>
        $("#input-image-1").fileinput({
            theme: 'fa',
            uploadUrl: "/admin/upload/images",
            allowedFileExtensions: ["jpg", "png", "gif",'jpeg'],
            maxImageWidth: 1000,
            minFileCount: 1,
            maxFileCount: 6,
            language: 'zh',
            overwriteInitial: false,
            resizeImage: true,
            initialPreviewAsData: true,
            initialPreview: [
                @foreach(array_filter(explode(',',$thisfixedtemplate->imagepics)) as $imagepic)
                    "{{$imagepic}}",
                @endforeach
            ],
            initialPreviewConfig: [
                    @foreach(array_filter(explode(',',$thisfixedtemplate->imagepics))  as $indexnum=>$imagepic)
                {caption: "{{$indexnum+1}}", url: "/admin/file-delete-batch", key: ['{{$imagepic}}']},
                @endforeach
            ],
            purifyHtml: true, // this by default purifies HTML data for preview
        }).on('fileuploaded', function(e, params) {
            $('#kv-success-box-imagepics').html('上传成功！');
            $('#kv-success-modal-imagepics').modal('show');
            $('.kv-file-remove').hide();
            $("#imagepics").val($("#imagepics").val()+params.response.link+',');
            $("#imagepics").val($("#imagepics").val().replace(',,',','));
        }).on("filepredelete", function() {
            var abort = true;
            if (confirm("确定要删除此图片吗 此删除操作为异步操作,删除后图片不可恢复,删除后请及时提交")) {
                abort = false;
            }
            return abort;
        }).on('filedeleted', function(event, key) {
            $("#imagepics").val($("#imagepics").val().replace(key,''));
            $("#imagepics").val($("#imagepics").val().replace(',,',','));
        });
    </script>
    <script>
        $("#input-image-2").fileinput({
            theme: 'fa',
            uploadUrl: "/admin/upload/images",
            allowedFileExtensions: ["jpg", "png", "gif",'jpeg'],
            maxImageWidth: 1000,
            minFileCount: 1,
            maxFileCount: 6,
            language: 'zh',
            overwriteInitial: false,
            resizeImage: true,
            initialPreviewAsData: true,
            initialPreview: [
                @foreach(array_filter(explode(',',$thisfixedtemplate->navpics)) as $navpic)
                    "{{$navpic}}",
                @endforeach
            ],
            initialPreviewConfig: [
                    @foreach(array_filter(explode(',',$thisfixedtemplate->navpics))  as $indexnum=>$navpic)
                {caption: "{{$indexnum+1}}", url: "/admin/file-delete-batch", key: ['{{$navpic}}']},
                @endforeach
            ],
            purifyHtml: true, // this by default purifies HTML data for preview
        }).on('fileuploaded', function(e, params) {
            $('#kv-success-box-navpics').html('上传成功！');
            $('#kv-success-modal-navpics').modal('show');
            $('.kv-file-remove').hide();
            $("#navpics").val($("#navpics").val()+params.response.link+',');
            $("#navpics").val($("#navpics").val().replace(',,',','));
        }).on("filepredelete", function() {
            var abort = true;
            if (confirm("确定要删除此图片吗 此删除操作为异步操作,删除后图片不可恢复,删除后请及时提交")) {
                abort = false;
            }
            return abort;
        }).on('filedeleted', function(event, key) {
            $("#navpics").val($("#navpics").val().replace(key,''));
            $("#navpics").val($("#navpics").val().replace(',,',','));
        });
    </script>
    <script>
        $("#input-image-3").fileinput({
            theme: 'fa',
            uploadUrl: "/admin/upload/images",
            allowedFileExtensions: ["jpg", "png", "gif",'jpeg'],
            maxImageWidth: 1000,
            minFileCount: 1,
            maxFileCount: 6,
            language: 'zh',
            overwriteInitial: false,
            resizeImage: true,
            initialPreviewAsData: true,
            initialPreview: [
                @foreach(array_filter(explode(',',$thisfixedtemplate->longpics)) as $longpic)
                    "{{$longpic}}",
                @endforeach
            ],
            initialPreviewConfig: [
                    @foreach(array_filter(explode(',',$thisfixedtemplate->longpics)) as $indexnum=>$longpic)
                {caption: "{{$indexnum+1}}", url: "/admin/file-delete-batch", key: ['{{$longpic}}']},
                @endforeach
            ],
            purifyHtml: true, // this by default purifies HTML data for preview
        }).on('fileuploaded', function(e, params) {
            $('#kv-success-box-longpics').html('上传成功！');
            $('#kv-success-modal-longpics').modal('show');
            $('.kv-file-remove').hide();
            $("#longpics").val($("#longpics").val()+params.response.link+',');
            $("#longpics").val($("#longpics").val().replace(',,',','));
        }).on("filepredelete", function() {
            var abort = true;
            if (confirm("确定要删除此图片吗 此删除操作为异步操作,删除后图片不可恢复,删除后请及时提交")) {
                abort = false;
            }
            return abort;
        }).on('filedeleted', function(event, key) {
            $("#longpics").val($("#longpics").val().replace(key,''));
            $("#longpics").val($("#longpics").val().replace(',,',','));
        });
    </script>
    <script>
        $("#input-image-4").fileinput({
            theme: 'fa',
            uploadUrl: "/admin/upload/images",
            allowedFileExtensions: ["jpg", "png", "gif",'jpeg'],
            maxImageWidth: 1000,
            minFileCount: 1,
            maxFileCount: 6,
            language: 'zh',
            overwriteInitial: false,
            resizeImage: true,
            initialPreviewAsData: true,
            initialPreview: [
                @foreach(array_filter(explode(',',$thisfixedtemplate->longtwopics)) as $longtwopic)
                    "{{$longtwopic}}",
                @endforeach
            ],
            initialPreviewConfig: [
                    @foreach(array_filter(explode(',',$thisfixedtemplate->longtwopics)) as $indexnum=>$longtwopic)
                {caption: "{{$indexnum+1}}", url: "/admin/file-delete-batch", key: ['{{$longtwopic}}']},
                @endforeach
            ],
            purifyHtml: true, // this by default purifies HTML data for preview
        }).on('fileuploaded', function(e, params) {
            $('#kv-success-box-longtwopics').html('上传成功！');
            $('#kv-success-modal-longtwopics').modal('show');
            $('.kv-file-remove').hide();
            $("#longtwopics").val($("#longtwopics").val()+params.response.link+',');
            $("#longtwopics").val($("#longtwopics").val().replace(',,',','));
        }).on("filepredelete", function() {
            var abort = true;
            if (confirm("确定要删除此图片吗 此删除操作为异步操作,删除后图片不可恢复,删除后请及时提交")) {
                abort = false;
            }
            return abort;
        }).on('filedeleted', function(event, key) {
            $("#longtwopics").val($("#longtwopics").val().replace(key,''));
            $("#longtwopics").val($("#longtwopics").val().replace(',,',','));
        });
    </script>
@stop

