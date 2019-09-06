<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel\Acreagement;
use App\AdminModel\Archive;
use App\AdminModel\Arctype;
use App\AdminModel\InvestmentType;
use App\Events\ArticleCacheCreateEvent;
use App\Events\ArticleCacheDeleteEvent;
use App\Http\Requests\CreateArticleRequest;
use App\Helpers\UploadImages;
use App\Notifications\ArticlePublishedNofication;
use App\Scopes\PublishedScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Log;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    /**文档列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function Index()
    {
        $articles = Archive::withoutGlobalScope(PublishedScope::class)->orderBy('updated_at','desc')->paginate(30);
        return view('admin.article',compact('articles'));
    }


    /**普通文档创建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function Create()
    {
        $allnavinfos=Arctype::where('is_write',1)->where('mid',0)->pluck('typename','id');
        return view('admin.article_create',compact('allnavinfos'));
    }


    /**文档创建提交数据处理
     * @param CreateArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function PostCreate(CreateArticleRequest $request)
    {
        if(Archive::withoutGlobalScope(PublishedScope::class)->where('title',$request->title)->value('id'))
        {
            exit('标题重复，禁止发布');
        }
        $this->RequestProcess($request);
        if ( Archive::create($request->all())->wasRecentlyCreated)
        {
            //百度相关数据提交
            $thisarticle=Archive::withoutGlobalScope(PublishedScope::class)->latest()->first();
            if ($thisarticle->published_at>Carbon::now() || $thisarticle->ismake !=1)
            {
                auth('admin')->user()->notify(new ArticlePublishedNofication($thisarticle));
                return redirect(action('Admin\ArticleController@Index'));
            }else{
                $thisarticleurl=config('app.url').'/news/'.$thisarticle->id.'.shtml';
                $miparticleurl=str_replace('www.','m.',config('app.url')).'/news/'.$thisarticle->id.'.shtml';
                $this->BaiduCurl(config('app.api'),$thisarticleurl,'百度主动提交');
                if ($request->xiongzhang)
                {
                    $this->BaiduCurl(config('app.mip_api'),$miparticleurl,'熊掌号天级推送');
                }else{
                    $this->BaiduCurl(config('app.mip_history'),$miparticleurl,'熊掌号周级提交');
                }
                Archive::where('id',$thisarticle->id)->update(['ispush'=>1]);
                auth('admin')->user()->notify(new ArticlePublishedNofication($thisarticle));
                event(new ArticleCacheCreateEvent(Archive::latest() ->first()));
                return redirect(action('Admin\ArticleController@Index'));
            }
        }
    }

    /**普通文档文档编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function Edit($id)
    {
        $articleinfos=Archive::withoutGlobalScope(PublishedScope::class)->findOrfail($id);
        $allnavinfos=Arctype::where('is_write',1)->where('mid',0)->pluck('typename','id');
        $pics=explode(',',trim(Archive::withoutGlobalScope(PublishedScope::class)->where('id',$id)->value('imagepics'),','));
        return view('admin.article_edit',compact('id','articleinfos','allnavinfos','pics'));
    }

    /**文档编辑提交处理
     * @param CreateArticleRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function PostEdit(CreateArticleRequest $request,$id)
    {
        $request['brandid']= !empty($request['bdname'])?Brandarticle::withoutGlobalScope(PublishedScope::class)->where('brandname','like','%'.$request['bdname'].'%')->value('id'):0;
        $request['brandid']=!empty($request['brandid'])?$request['brandid']:0;
        $this->RequestProcess($request);
        $thisarticleinfos=Archive::withoutGlobalScope(PublishedScope::class)->findOrFail($id);
        $request['write']=$thisarticleinfos->write;
        $request['dutyadmin']=$thisarticleinfos->dutyadmin;
        if ($thisarticleinfos->ismake || $thisarticleinfos->published_at>Carbon::now() || $request->ismake !=1 ||  Carbon::createFromFormat('Y-m-d',date('Y-m-d',strtotime($request['published_at']))) > Carbon::now())
        {
            Archive::withoutGlobalScope(PublishedScope::class)->findOrFail($id)->update($request->all());
            event(new ArticleCacheCreateEvent($thisarticleinfos));
            return redirect(action('Admin\ArticleController@Index'));
        }else{
            $request['created_at']=Carbon::now();
            $request['updated_at']=Carbon::now();
            $request['ispush']=1;
            Archive::withoutGlobalScope(PublishedScope::class)->findOrFail($id)->update($request->all());
            $thisarticleurl=config('app.url').'/news/'.$thisarticleinfos->id.'.shtml';
            $miparticleurl=str_replace('www.','m.',config('app.url')).'/news/'.$thisarticleinfos->id.'.shtml';
            $this->BaiduCurl(config('app.api'),$thisarticleurl,'审核后百度主动提交');
            if ($request->xiongzhang)
            {
                $this->BaiduCurl(config('app.mip_api'),$miparticleurl,'审核后熊掌号天级推送');
            }else{
                $this->BaiduCurl(config('app.mip_history'),$miparticleurl,'审核后熊掌号周级提交');
            }
            event(new ArticleCacheCreateEvent($thisarticleinfos));
            return redirect(action('Admin\ArticleController@Index'));
        }
    }

    /**
     *自定义文档属性及缩略图处理
     * @param Request $request
     * @return Request
     */
    private function RequestProcess(Request $request)
    {
        $request['keywords']=$request['keywords']?$request['keywords']:$request['title'];
        $request['click']=rand(100,900);
        $request['description']=(!empty($request['description']))?str_limit($request['description'],180,''):str_limit(str_replace(['&nbsp;',' ','　',PHP_EOL,'\t'],'',strip_tags(htmlspecialchars_decode($request['body']))), $limit = 180, $end = '');
        $request['write']=auth('admin')->user()->name;
        $request['dutyadmin']=auth('admin')->id();
        $request['body']=str_replace('<h2></h2>','',$request->body);
        //自定义文档属性处理
        if(isset($request['flags']))
        {
            $request['flags']=UploadImages::Flags($request['flags']);
        }
        //缩略图处理
        if($request['image'])
        {
            $request['litpic']=UploadImages::UploadImage($request,'image');
            if(empty($request['flags']))
            {
                $request['flags'].='p';
            }else{
                $request['flags'].=',p';
            }
        }elseif (isset($request['litpic']) && !empty($request['litpic']))
        {
            $request['litpic']=$request['litpic'];
        }elseif (preg_match('/<[img|IMG].*?src=[\' | \"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/i',$request['body'],$match)){
            $request['litpic']=$match[1];
            if(empty($request['flags']))
            {
                $request['flags'].='p';
            }else{
                $request['flags'].=',p';
            }
        }
        //首页推荐图处理
        if($request['indexlitpic']) {
            $request['indexpic'] = UploadImages::UploadImage($request, 'indexlitpic');
        }
        //图集处理
        $request['imagepics']=trim($request->input('imagepics'),',');
        return $request;

    }

    /**品牌图集提取
     * @param $content
     * @return string
     */
    private function processImagepics($content)
    {
        preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU", $content, $matches);
        if (isset($matches[2]) && !empty($matches[2]) ) {
            $imagepics = array_slice($matches[2],0,4);
            $pics='';
            foreach ($imagepics as $imagepic) {
                $pics.=$imagepic.',';
            }
            return trim($pics,',');
        }
    }
    /**当前用户发布的文档
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function OwnerShip()
    {
        $articles = Archive::withoutGlobalScope(PublishedScope::class)->where('dutyadmin',auth('admin')->user()->id)->latest()->paginate(30);
        return view('admin.article',compact('articles'));
    }

    /**等待审核的文档
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function PendingAudit()
    {
        $articles = Archive::withoutGlobalScope(PublishedScope::class)->where('ismake','<>',1)->latest()->paginate(30);
        return view('admin.article',compact('articles'));
    }




    /**等待发布的文档
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function PedingPublished(){
        $articles = Archive::withoutGlobalScope(PublishedScope::class)->where('published_at','>',Carbon::now())->latest()->paginate(30);
        return view('admin.article',compact('articles'));
    }


    /**普通文档删除
     * @param $id
     * @return string
     */
    function DeleteArticle($id)
    {
        if(auth('admin')->user()->id)
        {
            event(new ArticleCacheDeleteEvent(Archive::withoutGlobalScope(PublishedScope::class)->where('id',$id)->first()));
            Archive::withoutGlobalScope(PublishedScope::class)->where('id',$id)->delete();
            return '删除成功';
        }else{
            return '无权限执行此操作！';
        }
    }


    /**文档搜索
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function PostArticleSearch(Request $request)
    {
        $articles=Archive::withoutGlobalScope(PublishedScope::class)->where('title','like','%'.$request->input('title').'%')->latest()->paginate(30);
        $title=$request->title;
        return view('admin.article',compact('articles','title'));
    }


    /** 栏目文章查看
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function Type($id)
    {
        switch (Arctype::where('id',$id)->value('mid'))
        {
            case 0:
                $articles=Archive::withoutGlobalScope(PublishedScope::class)->where('typeid',$id)->latest()->paginate(30);
                $view='admin.article';
                break;
        }
        return view($view,compact('articles'));
    }


    /**百度主动推送
     * @param $thisarticleurl
     * @param $token
     * @param $type
     */
    private function BaiduCurl($token,$thisarticleurl,$type)
    {
        $urls = array($thisarticleurl);
        $ch = curl_init();
        $options =  array(
            CURLOPT_URL =>$token,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        Log::info($thisarticleurl.$type);
        Log::info($result);
    }

    /**重复标题检测
     * @param Request $request
     * @return int
     */
    public function ArticletitleCheck(Request $request)
    {
        $title=Archive::withoutGlobalScope(PublishedScope::class)->where('title',$request->input('title'))->count();
        return $title?1:0;
    }

}
