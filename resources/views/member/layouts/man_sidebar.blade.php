<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{auth('web')->user()->name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
            </div>
        </div>
        <!-- search form -->
        <form action="/admin/article/search" method="post" class="sidebar-form">
            {{ csrf_field() }}
            <div class="input-group">
                <input type="text" name="title" class="form-control" autocomplete="off" placeholder="输入文档标题...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">会员信息电话管理</li>
            <li>
                <a href="#">
                    <i class="fa fa-random"></i> <span>数据分配管理</span>
                    <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Request::getRequestUri()=='/admin/flink')class="active"@endif><a href="/admin/flink"><i class="fa fa-circle-o"></i> 我的品牌数据</a></li>
                    <li @if(Request::getRequestUri()=='/admin/flink/create')class="active"@endif><a href="/admin/flink/create"><i class="fa fa-circle-o"></i> 数据分类汇总</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa  fa-file-text"></i> <span>会员中心管理</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Request::getRequestUri()=='/member/index')class="active"@endif><a href="/member/index" ><i class="fa fa-circle-o"></i> 当前会员信息</a></li>
                    <li @if(Request::getRequestUri()=='/member/infoedit')class="active"@endif><a href="/member/infoedit" ><i class="fa fa-circle-o"></i> 会员信息修改</a></li>
                    <li @if(Request::getRequestUri()=='/member/repassword')class="active"@endif><a href="/member/repassword" ><i class="fa fa-circle-o"></i> 会员密码修改</a></li>
                    <li @if(Request::getRequestUri()=='/member/expensesrecord')class="active"@endif><a href="/member/expensesrecord" ><i class="fa fa-circle-o"></i> 会员消费记录</a></li>
                    <li @if(Request::getRequestUri()=='/member/charhistory')class="active"@endif><a href="/member/charhistory" ><i class="fa fa-circle-o"></i> 会员充值记录</a></li>
                    <li @if(Request::getRequestUri()=='/member/category')class="active"@endif><a href="/member/category" ><i class="fa fa-circle-o"></i> 会员登陆记录</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa  fa-database"></i> <span>品牌资讯管理</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(Request::getRequestUri()=='/admin/brandarticle/receive')class="active"@endif><a href="/admin/brandarticle/receive" ><i class="fa fa-circle-o"></i> 我的品牌内容</a></li>
                    <li @if(Request::getRequestUri()=='/admin/brandarticle/receive')class="active"@endif><a href="/admin/brandarticle/receive" ><i class="fa fa-circle-o"></i> 我的品牌资讯</a></li>
                 </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
