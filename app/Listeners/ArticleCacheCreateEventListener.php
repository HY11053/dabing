<?php

namespace App\Listeners;

use App\AdminModel\Acreagement;
use App\AdminModel\Archive;
use App\AdminModel\Arctype;
use App\AdminModel\Brandarticle;
use App\AdminModel\InvestmentType;
use App\Events\ArticleCacheCreateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class ArticleCacheCreateEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ArticleCacheCreateEvent  $event
     * @return void
     */
    public function handle(ArticleCacheCreateEvent $event)
    {
        $id=$event->arcvhive->id;
        if (Archive::find($id))
        {
            //清除当前缓存 重新写入 兼容Update
            Cache::forget('thisarticleinfos_'.$id);
            //获取当前文档并缓存
            $thisarticleinfos = Cache::remember('thisarticleinfos_'.$id, config('app.cachetime')+rand(60,60*24), function() use($id){
                return Archive::findOrFail($id);
            });
            //获取当前栏目信息并缓存
            $thistypeinfo=Cache::remember('thistypeinfos_'.$thisarticleinfos->typeid, config('app.cachetime')+rand(60,60*24), function() use($id,$thisarticleinfos){
                return Arctype::where('id',$thisarticleinfos->typeid)->first();
            });
            //获取文档上一篇并缓存
            Cache::remember('thisarticleinfos_prev'.$id, config('app.cachetime')+rand(60,60*24), function() use($thisarticleinfos){
                return Archive::latest('created_at')->where('id',$this->getPrevArticleId($thisarticleinfos->id))->first(['title','id']);
            });
            //获取文档下一篇并缓存 此时下一篇为空
            Cache::remember('thisarticleinfos_next'.$id, config('app.cachetime')+rand(60,60*24), function() use($thisarticleinfos){
                return Archive::latest('created_at')->where('id',$this->getNextArticleId($thisarticleinfos->id))->first(['title','id']);
            });
            //更新上一篇文档的下一篇缓存
            $prev_id=$this->getPrevArticleId($id);
            Cache::forget('thisarticleinfos_next'.$prev_id);
            Cache::remember('thisarticleinfos_next'.$prev_id, config('app.cachetime')+rand(60,60*24), function() use($thisarticleinfos){
                return Archive::latest('created_at')->where('id',$thisarticleinfos->id)->first(['title','id']);
            });
            //获取当前文档所属品牌并缓存
            if($thisarticleinfos->brandid && Brandarticle::where('id',$thisarticleinfos->brandid)->orderBy('id','desc')->value('id'))
            {
                //清除当前缓存 重新写入 兼容Update
                Cache::forget('thisbrandarticleinfos_'.$thisarticleinfos->brandid);
                $thisarticlebrandinfos = Cache::remember('thisbrandarticleinfos_'.$thisarticleinfos->brandid, config('app.cachetime')+rand(60,60*24), function() use($id,$thisarticleinfos){
                    return Brandarticle::where('id',$thisarticleinfos->brandid)->first();
                });
            }
            //获取当前文档所属品牌分类
            if (isset($thisarticlebrandinfos) && !empty($thisarticlebrandinfos))
            {
                //清除当前缓存 重新写入 兼容Update
                Cache::forget('thistypeinfos_'.$thisarticlebrandinfos->typeid);
                //当前文档所属品牌所属分类
                Cache::remember('thistypeinfos_'.$thisarticlebrandinfos->typeid, config('app.cachetime')+rand(60,60*24), function() use($thisarticlebrandinfos){
                    return  Arctype::where('id',$thisarticlebrandinfos->typeid)->first();
                });
                //品牌新闻
                Cache::forget('thisarticleinfos_brandnews'.$thisarticlebrandinfos->id);
                $latestbrandnews=Cache::remember('thisarticleinfos_brandnews'.$thisarticlebrandinfos->id, config('app.cachetime')+rand(60,60*24), function() use($thisarticleinfos,$thisarticlebrandinfos){
                    $brandnews=Archive::where('brandid',$thisarticleinfos->brandid)->take(10)->latest()->get(['id','title','created_at','litpic']);
                    if ($brandnews->count()<10)
                    {
                        $completionnews=Archive::whereIn('brandid',Brandarticle::where('typeid',$thisarticlebrandinfos->typeid)->pluck('id'))->whereNotIn('id',Archive::where('brandid',$thisarticleinfos->brandid)->pluck('id'))->take(10-($brandnews->count()))->latest()->get(['id','title','created_at','litpic']);
                    }else{
                        $completionnews=collect([]);
                    }
                    $latestbrandnews=collect([$brandnews,$completionnews])->collapse();
                    return $latestbrandnews;
                });
                //品牌新闻右侧
                Cache::forget('brandtypenews'.$thisarticlebrandinfos->id);
                Cache::remember('brandtypenews'.$thisarticlebrandinfos->id, config('app.cachetime')+rand(60,60*24), function() use($thisarticleinfos,$latestbrandnews,$thisarticlebrandinfos){
                    $notids=[];
                    foreach ($latestbrandnews as $latestbrandnew)
                    {
                        $notids[]=$latestbrandnew->id;
                    }
                    return Archive::whereIn('brandid',Brandarticle::where('typeid',$thisarticlebrandinfos->typeid)->pluck('id'))->whereNotIn('id',$notids)->take(10)->latest('created_at')->get(['id', 'title','litpic','created_at']);
                });

            }else{
                Cache::forget('thisarticleinfos_typebrandnews'.$thisarticleinfos->typeid);
                Cache::remember('thisarticleinfos_typebrandnews'.$thisarticleinfos->typeid, config('app.cachetime')+rand(60,60*24), function() use($thisarticleinfos) {
                    return Archive::where('typeid', $thisarticleinfos->typeid)->take(10)->latest()->get(['id', 'title', 'created_at','litpic']);
                });
                Cache::forget('typenews'.$thisarticleinfos->typeid);
                Cache::remember('typenews'.$thisarticleinfos->typeid,  config('app.cachetime')+rand(60,60*24), function() use($thisarticleinfos) {
                    return  Archive::where('typeid', $thisarticleinfos->typeid)->take(5)->latest('created_at')->get(['id', 'title','litpic','created_at']);
                });
            }
            //首页缓存
            Cache::forget('index_asknews');
            Cache::remember('index_asknews', config('app.cachetime')+rand(60,60*24), function(){
                return Archive::orderBy('id','desc')->inRandomOrder()->take(4)->get(['id','title','description']);
            });
            Cache::forget('index_latestnews');
            Cache::remember('index_latestnews', config('app.cachetime')+rand(60,60*24), function(){
                return Archive::orderBy('id','desc')->where('typeid',3)->latest()->take(14)->get(['id','title','description','litpic']);
            });
            Cache::forget('index_jmfnews');
            Cache::remember('index_jmfnews', config('app.cachetime')+rand(60,60*24), function(){
                return Archive::orderBy('id','desc')->where('typeid',8)->latest()->take(10)->get(['id','title','created_at']);
            });
            Cache::forget('index_cynews');
            Cache::remember('index_cynews', config('app.cachetime')+rand(60,60*24), function(){
                return Archive::orderBy('id','desc')->where('typeid',10)->latest()->take(8)->get(['id','title','created_at','litpic']);
            });
            Cache::forget('index_touzinews');
            Cache::remember('index_touzinews', config('app.cachetime')+rand(60,60*24), function(){
                return Archive::orderBy('id','desc')->where('typeid',9)->latest()->take(9)->get(['id','title','created_at','litpic']);
            });
            //移动端首页
            Cache::forget('mobile_index_latestnews');
            Cache::remember('mobile_index_latestnews', config('app.cachetime')+rand(60,60*24), function(){
                return Archive::orderBy('id','desc')->take(7)->get(['id','title','litpic','created_at']);
            });
            //列表页
            Cache::forget('platestnews'.$thistypeinfo->id);
            Cache::remember('platestnews'.$thistypeinfo->id, config('app.cachetime')+rand(60,60*24), function() use($thistypeinfo){
                return Archive::where('typeid','<>',$thistypeinfo->id)->take(10)->latest()->get();
            });
            Cache::forget('cnewslists'.$thistypeinfo->id);
            Cache::remember('cnewslists'.$thistypeinfo->id,  rand(10,60), function() use($thistypeinfo){
                return Archive::whereIn('brandid',Brandarticle::where('typeid',$thistypeinfo->id)->latest()->pluck('id'))->take(13)->latest()->get(['id','title']);
            });
            //移动端列表页
            Cache::forget('mtype_latestnews'.$thistypeinfo->id);
            Cache::remember('mtype_latestnews'.$thistypeinfo->id, config('app.cachetime')+rand(60,60*24), function() use($thistypeinfo){
                return Archive::where('typeid','<>',$thistypeinfo->id)->take(7)->latest()->get();
            });
            Cache::forget('m_cnewslists'.$thistypeinfo->id);
            Cache::remember('m_cnewslists'.$thistypeinfo->id,  rand(10,60), function() use($thistypeinfo){
                return Archive::whereIn('brandid',Brandarticle::where('typeid',$thistypeinfo->id)->latest()->pluck('id'))->take(7)->latest()->get(['id','title','litpic']);
            });
            //移动端杂项
            Cache::remember('m_cnewslists'.$thistypeinfo->id,  rand(10,60), function() use($thistypeinfo){
                return Archive::whereIn('brandid',Brandarticle::where('typeid',$thistypeinfo->id)->latest()->pluck('id'))->take(7)->latest()->get(['id','title','litpic']);
            });
            Cache::forget('acreagements');
            Cache::remember('acreagements',  config('app.cachetime')+rand(60,60*24), function(){
                return Acreagement::pluck('type','id');
            });
            Cache::forget('investments');
            Cache::remember('investments',  config('app.cachetime')+rand(60,60*24), function(){
                return InvestmentType::orderBy('id','asc')->pluck('type','id');
            });
        }
    }
    protected function getPrevArticleId($id)
    {
        return Archive::where('id', '<', $id)->orderBy('id','desc')->value('id');
    }
    protected function getNextArticleId($id)
    {
        return Archive::where('id', '>', $id)->orderBy('id','asc')->value('id');
    }
}
