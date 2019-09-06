<?php

namespace App\Http\Controllers\Api;

use App\AdminModel\Addonarticle;
use App\AdminModel\Archive;
use App\AdminModel\Arctype;
use App\AdminModel\Brandarticle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleSourcesController extends Controller
{
    /**普通文档调用接口
     * @param Request $request
     * @return string
     */
    public function FetchArticles(Request $request)
    {
        $articles=Archive::when($request->id, function ($query) use ($request) {
            return $query->where('id',$request->id);
        })->when($request->typeid, function ($query) use ($request) {
            return $query->where('typeid',$request->typeid);
        })->when($request->flags, function ($query) use ($request) {
            return $query->where('flags','like','%'.$request->flags.'%');
        })->when($request->orderby, function ($query) use ($request) {
            return $query->OrderBy($request->orderby,'desc');
        })->when($request->brandpic, function ($query) use ($request) {
            return $query->where('brandid','<>',0);
        })->when($request->brandid, function ($query) use ($request) {
            return $query->where('brandid',$request->brandid);
        })->when($request->litpic, function ($query) use ($request) {
            return $query->where('litpic','<>','');
        })->when($request->bid, function ($query) use ($request) {
            return $query->where('brandid',Archive::where('id',$request->aid)->value('brandid'))->where('id','<>',$request->aid);
        })->when($request->skip, function ($query) use ($request) {
            return $query->skip($request->skip);
        })->when($request->take, function ($query) use ($request) {
            return $query->take($request->take);
        }, function ($query) {
            return $query->take(1);
        })->get(['id','typeid','brandid','litpic','created_at','title','description','keywords'])->toArray();
        if (!empty($articles))
        {
            foreach ($articles as $picindex=>$article)
            {
                if (!str_contains($article['litpic'],'://'))
                {
                    $articles[$picindex]['litpic']=config('app.url').$article['litpic'];
                }
            }
            if ($request->brandpic)
            {
                foreach ($articles as $key=>$article){
                    $imagepics=Brandarticle::where('id',$article['brandid'])->value('imagepics');
                    if (!empty($imagepics)){
                        $articles[$key]['imagepics']=$this->processImgPath($imagepics);
                    }
                }
            }
        }
        return !empty($articles)?json_encode($articles):'';
    }

    /**
     * 品牌文档调用接口
     * @param Request $request
     * @return mixed
     */
    public function FetchBrandArticles(Request $request)
    {
        $articles=Brandarticle::when($request->id, function ($query) use ($request) {
            return $query->where('id',$request->id);
        })->when($request->typeid, function ($query) use ($request) {
            return $query->whereIn('typeid',Arctype::where('reid',$request->typeid)->pluck('id'))->orWhere('typeid',$request->typeid);
        })->when($request->tid, function ($query) use ($request) {
            return $query->where('typeid',Brandarticle::where('id',$request->aid)->value('typeid'));
        })->when($request->bid, function ($query) use ($request) {
            return $query->where('typeid',Brandarticle::where('id',Archive::where('id',$request->bid)->value('brandid'))->value('typeid'));
        })->when($request->flags, function ($query) use ($request) {
            return $query->where('flags','like','%'.$request->flags.'%');
        })->when($request->orderby, function ($query) use ($request) {
            return $query->OrderBy($request->orderby,'desc');
        })->when($request->skip, function ($query) use ($request) {
            return $query->skip($request->skip);
        })->when($request->take, function ($query) use ($request) {
            return $query->take($request->take);
        }, function ($query) {
            return $query->take(1);
        })->get(['id','title','description','keywords','typeid','litpic','created_at','brandname','brandgroup','brandpay','imagepics','brandorigin','brandattch','brandnum','click'])->toarray();
        if (!empty($articles))
        {
            foreach ($articles as $key=>$article){
                //日期处理
                $articles[$key]['created_at']=date('y-m-d',strtotime($article['created_at']));
                //缩略图路径处理
                if (!str_contains($article['litpic'],'://'))
                {
                    $articles[$key]['litpic']=config('app.url').$article['litpic'];
                }
                $articles[$key]['imagepics']=$this->processImgPath($articles[$key]['imagepics']);
            }
        }
        return !empty($articles)?json_encode($articles):'';
    }
    /**获取单篇普通文档
     * @param Request $request
     * @return mixed
     */
    public function getArticle(Request $request)
    {
        $article=Archive::when($request->id, function ($query) use ($request) {
            return $query->where('id',$request->id);
        })->first()->toArray();
        if (!empty($article))
        {
            $article['body']=$this->processContent($article['body']);
            if (!empty($article['litpic']) && !str_contains($article['litpic'],'://'))
            {
                $article['litpic']=config('app.url').$article['litpic'];
            }
            $brandarticle=Brandarticle::where('id',$article['brandid'])->first();
            if ($article['brandid'] && !empty($brandarticle))
            {
                $article['brandname']=$brandarticle->brandname;
                $article['brandtypename']=$brandarticle->arctype->typename;
                $article['brandgroup']=$brandarticle->brandgroup;
                $article['brandpay']=$brandarticle->brandpay;
                $article['brandcreated']=date('y-m-d',strtotime($brandarticle->created_at));
                $article['brandimages']=$this->processImgPath($brandarticle->imagepics);
                $article['brandorigin']=$brandarticle->brandorigin;
                $article['brandnum']=$brandarticle->brandnum;
                $article['brandclick']=$brandarticle->click;
            }
        }
        return !empty($article)?json_encode($article):'';
    }

    /**获取单篇品牌文档
     * @param Request $request
     * @return mixed
     */
    public function getBrandarticle(Request $request)
    {
        $article=Brandarticle::when($request->id, function ($query) use ($request) {
            return $query->where('id',$request->id);
        })->first()->toArray();
        if (!empty($article))
        {
            //$article['created_at']=date('y-m-d',strtotime($article['created_at']));
            $article['imagepics']=$this->processImgPath($article['imagepics']);
            if (!empty($article['litpic']) && !str_contains($article['litpic'],'://'))
            {
                $article['litpic']=config('app.url').$article['litpic'];
            }
            $article['body']=$this->processContent( $article['body']);
        }
        //dd($article['body']);
        return !empty($article)?json_encode($article):'';
    }

    /**栏目获取
     * @param Request $request
     * @return mixed
     */
    public function getBnlist(Request $request)
    {
        if ($request->real_path && Arctype::where('real_path',trim($request->real_path,'/'))->first())
        {
            $thistypeinfos=Arctype::where('real_path',trim($request->real_path,'/'))->first()->toJson();
        }else{
            $thistypeinfos=Arctype::inRandomOrder()->first()->toJson();
        }
        return $thistypeinfos;
    }

    /**当前文档栏目信息获取
     * @param Request $request
     * @return mixed
     */
    public function getTypeinfo(Request $request)
    {
        if ($request->aid)
        {
            $thistypeinfos=Arctype::where('id',Archive::where('id',$request->aid)->value('typeid'))->first()->toJson();
        }elseif($request->bid){
            $thistypeinfos=Arctype::where('id',Brandarticle::where('id',$request->bid)->value('typeid'))->first()->toJson();
        }
        return $thistypeinfos;
    }

    /**品牌文档列表
     * @param Request $request
     * @return false|string
     */
    public function getBnlistArticles(Request $request)
    {
        $articles=Brandarticle::whereIn('typeid',Arctype::where('reid',Arctype::where('real_path',$request->real_path)->value('id'))->pluck('id'))->orWhere('typeid',Arctype::where('real_path',$request->real_path)->value('id'))->take(15)->latest()->get(['id','title','description','keywords','typeid','litpic','created_at','brandname','brandgroup','brandpay','brandorigin','brandattch','brandnum','click'])->toArray();
        if (!empty($articles))
        {
            foreach ($articles as $picindex=>$article)
            {
                if (!str_contains($article['litpic'],'://'))
                {
                    $articles[$picindex]['litpic']=config('app.url').$article['litpic'];
                }
            }
        }
        return !empty($articles)?json_encode($articles):'';
    }

    /**
     *
     */
    public function paiHangBangType(Request $request)
    {
        if (!$request->real_path)
        {
            $thistypeinfos=Arctype::where('id',90)->first()->toArray();
            $thistypeinfos['id']=6;
            json_encode($thistypeinfos);
        }else{
            $thistypeinfo=Arctype::where('real_path',$request->real_path)->first();
            $thistypeinfos=[];
            $thistypeinfos['id']=$thistypeinfo->id;
            $thistypeinfos['title']=str_replace('加盟','',$thistypeinfo->typename).'加盟排行榜_'.str_replace('加盟','',$thistypeinfo->typename).'品牌排行榜-中教招商网';
            $thistypeinfos['keywords']=str_replace('加盟','',$thistypeinfo->typename).'加盟排行榜,'.str_replace('加盟','',$thistypeinfo->typename).'品牌排行榜'.str_replace('加盟','',$thistypeinfo->typename).'品牌排行';
            $thistypeinfos['description']='火爆餐饮网为您全方位解读'.str_replace('加盟','',$thistypeinfo->typename).'加盟品牌排行信息。分享'.str_replace('加盟','',$thistypeinfo->typename).'门店信息，'.str_replace('加盟','',$thistypeinfo->typename).'排行榜分类提供性价比最高'.str_replace('加盟','',$thistypeinfo->typename).'品牌加盟排行榜信息，供你快速的选择适合自己的'.str_replace('加盟','',$thistypeinfo->typename).'加盟品牌';
            json_encode($thistypeinfos);
        }
        return $thistypeinfos;
    }
    /**排行榜数据请求
     * @param Request $request
     * @param string $path
     * @return false|string
     */
    public function paiHangBang(Request $request)
    {
        $articles=Brandarticle::when($request->real_path, function ($query) use ($request) {
            return $query->whereIn('typeid', Arctype::where('reid', Arctype::where('real_path', $request->real_path)->value('id'))->pluck('id'))->orwhere('typeid', Arctype::where('real_path', $request->real_path)->value('id'));
        })->orderBy('click', 'desc')->take(10)->get(['id','title','description','keywords','typeid','litpic','created_at','brandname','brandgroup','brandpay','imagepics','brandorigin','brandattch','brandnum','click'])->toArray();
        if (!empty($articles))
        {
            foreach ($articles as $picindex=>$article)
            {
                if (!str_contains($article['litpic'],'://'))
                {
                    $articles[$picindex]['litpic']=config('app.url').$article['litpic'];
                }
            }
        }
        return !empty($articles)?json_encode($articles):'';
    }

    /**品牌文档搜索列表
     * @param Request $request
     * @return false|string
     */
    public function getSearchBnlist(Request $request)
    {
        $articles=Brandarticle::where('title','like','%'.$request->keyword.'%')->when($request->skip, function ($query) use ($request) {
            return $query->skip($request->skip);
        })->take(5)->orderBy('click','desc')->get(['id','title','description','keywords','typeid','litpic','created_at','brandname','brandgroup','brandpay','imagepics','brandorigin','brandattch','brandnum','click'])->toArray();
        if (!empty($articles))
        {
            foreach ($articles as $picindex=>$article)
            {
                if (!str_contains($article['litpic'],'://'))
                {
                    $articles[$picindex]['litpic']=config('app.url').$article['litpic'];
                }
            }
        }
        return !empty($articles)?json_encode($articles):'';
    }

    /**普通文档列表
     * @param Request $request
     * @return false|string
     */
    public function getNlistArticles(Request $request)
    {
        $articles=Archive::whereIn('typeid',Arctype::where('reid',Arctype::where('real_path',$request->real_path)->value('id'))->pluck('id'))->orWhere('typeid',Arctype::where('real_path',$request->real_path)->value('id'))->take(15)->latest()->get(['id','typeid','brandid','litpic','created_at','title','description','keywords'])->toArray();
        if (!empty($articles))
        {
            foreach ($articles as $picindex=>$article)
            {
                if (!str_contains($article['litpic'],'://'))
                {
                    $articles[$picindex]['litpic']=config('app.url').$article['litpic'];
                }
            }
        }
        return !empty($articles)?json_encode($articles):'';
    }

    /**图片路径处理
     * @param $path
     * @return 0|array
     */
    private function processImgPath($path)
    {
        if (!empty($path))
        {
            $pics=array_slice(array_filter(explode(",",$path)),0,3);
            //图集路径处理
            foreach ($pics as $index=>$pic)
            {
                if (!str_contains($pic,'://'))
                {
                    $pics[$index]=config('app.url').$pic;
                }
            }
            return $pics;
        }

    }

    /**文档内容格式清除
     * @param $content
     * @return null|string|string[]
     */
    private function processContent($content)
    {
        if (!empty($content))
        {
            $content=preg_replace(["/style=.+?['|\"]/i","/width=.+?['|\"]/i","/height=.+?['|\"]/i"],'',$content);
            $content=str_replace(PHP_EOL,'',$content);
            $content=$this->contentImagePathProcess($content,config('app.url'));
            return $content;
        }
    }

    /**内容图片路径匹配替换
     * @param $content
     * @param $url
     * @return mixed
     */
    private function contentImagePathProcess($content,$url)
    {
        //dd($content);
        preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU", $content, $matches);
        if (!empty($matches)) {
            $imgurl = $matches[2];
            foreach ($imgurl as $val) {
                if (!str_contains($val,'://'))
                {
                    $content = str_replace($val, $url.$val, $content);
                }
            }
            return $content;
        } else {
            return $content;
        }
    }
}
