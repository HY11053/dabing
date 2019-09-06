<?php

namespace App\Http\Controllers\Frontend;

use App\AdminModel\Acreagement;
use App\AdminModel\Archive;
use App\AdminModel\Arctype;
use App\AdminModel\Brandarticle;
use App\AdminModel\Flink;
use App\AdminModel\InvestmentType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    function Index()
    {
        //幻灯底部推荐
        $muyingnavlists=Cache::remember('index_muyingnavlists', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',1)->orderBy('id','asc')->take(10)->get(['id','brandname']);
        });
        //母婴生活馆
        $muyingshgnavlists=Cache::remember('index_muyingshgnavlists', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',2)->orderBy('id','asc')->take(10)->get(['id','brandname']);
        });
        //品牌招商
        $muyingypnavlists=Cache::remember('index_muyingypnavlists', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',4)->orderBy('id','asc')->take(10)->get(['id','brandname']);
        });

        $latestbrands=Cache::remember('index_latestbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid','<',3)->take(5)->latest()->get(['id','brandname','litpic','brandpay','description']);
        });
        $cbrands=Cache::remember('index_cbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::whereIn('id',[23,13,40,14])->latest()->get(['id','brandname','indexpic','brandpsp','description']);
        });
        $hotbrands=Cache::remember('index_hotbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',2)->skip(10)->take(5)->orderBy('id','asc')->get(['id','brandname']);
        });
        $lefthotbrands=Cache::remember('index_lefthotbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',1)->skip(10)->take(7)->orderBy('id','asc')->get(['id','litpic','brandname']);
        });
        $sbrands=Cache::remember('index_sbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('flags','like','%s%')->take(10)->orderBy('id','asc')->get(['id','brandname','brandpay','indexpic']);
        });
        $brandtypes=Cache::remember('index_brandtypes', config('app.cachetime')+rand(60,60*24), function(){
            return   Arctype::where('mid',1)->take(8)->get(['typename','real_path']);
        });
        $latestmuyingbrands=Cache::remember('index_latestmuyingbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',1)->take(27)->orderBy('id','desc')->get(['id','brandname']);
        });
        $latestrmuyingbrands=Cache::remember('index_latestrmuyingbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',1)->skip(27)->take(60)->orderBy('id','desc')->get(['id','brandname']);
        });
        $latestmuyingshgbrands=Cache::remember('index_latestmuyingshgbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',2)->take(27)->orderBy('id','desc')->get(['id','brandname']);
        });
        $latestrmuyingshgbrands=Cache::remember('index_latestrmuyingshgbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',2)->skip(27)->take(18)->orderBy('id','desc')->get(['id','brandname','litpic']);
        });
        $latestzsbrands=Cache::remember('index_latestzsbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',4)->take(27)->orderBy('id','desc')->get(['id','brandname']);
        });
        $latestrzsbrands=Cache::remember('index_latestrzsbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',4)->skip(27)->take(18)->orderBy('id','desc')->get(['id','brandname','litpic']);
        });
        $hbrands=Cache::remember('index_hbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::take(5)->orderBy('click','desc')->get(['id','brandname','brandpay','litpic','brandattch','brandapply','typeid']);
        });
        $brandalltypes=Cache::remember('index_brandalltypes', config('app.cachetime')+rand(60,60*24), function(){
            return Arctype::orderBy('id','desc')->pluck('typename','id');
        });
        //加盟小百科
        $asknews=Cache::remember('index_asknews', config('app.cachetime')+rand(60,60*24), function(){
            return Archive::orderBy('id','desc')->inRandomOrder()->take(4)->get(['id','title','description']);
        });
        $latestnews=Cache::remember('index_latestnews', config('app.cachetime')+rand(60,60*24), function(){
            return Archive::orderBy('id','desc')->where('typeid',3)->latest()->take(14)->get(['id','title','description','litpic']);
        });
        $jmfnews=Cache::remember('index_jmfnews', config('app.cachetime')+rand(60,60*24), function(){
            return Archive::orderBy('id','desc')->where('typeid',8)->latest()->take(10)->get(['id','title','created_at']);
        });
        $cynews=Cache::remember('index_cynews', config('app.cachetime')+rand(60,60*24), function(){
            return Archive::orderBy('id','desc')->where('typeid',10)->latest()->take(8)->get(['id','title','created_at','litpic']);
        });
        $touzinews=Cache::remember('index_touzinews', config('app.cachetime')+rand(60,60*24), function(){
            return Archive::orderBy('id','desc')->where('typeid',9)->latest()->take(9)->get(['id','title','created_at','litpic']);
        });
        //友情链接
        $flinks=Cache::remember('index_flinks', 60, function(){
            return   Flink::latest()->orderBy('created_at','desc')->take(30)->get();
        });
        //店铺面积缓存
        $acreagements=Cache::remember('acreagements',  config('app.cachetime')+rand(60,60*24), function(){
            return Acreagement::pluck('type','id');
        });
        $investments=Cache::remember('investments',  config('app.cachetime')+rand(60,60*24), function(){
            return InvestmentType::orderBy('id','asc')->pluck('type','id');
        });
        $touziids=Cache::remember('touziids',  config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::select('tzid')->distinct()->pluck('tzid');
        });

        return view('frontend.index',compact('muyingnavlists','muyingshgnavlists','muyingypnavlists','latestbrands','cbrands','hotbrands',
            'lefthotbrands','sbrands','brandtypes','latestmuyingbrands','latestrmuyingbrands','latestmuyingshgbrands','latestrmuyingshgbrands','latestrzsbrands','latestzsbrands',
            'latestzsbrands','hbrands','brandalltypes','asknews','latestnews','jmfnews','cynews','touzinews','flinks','acreagements','investments','touziids'));
    }

}
