<?php

namespace App\Http\Controllers\Mobile;

use App\AdminModel\Archive;
use App\AdminModel\Brandarticle;
use App\AdminModel\Production;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    function Index()
    {
        $cbrands=Cache::remember('mobile_index_cbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::whereIn('id',[23,13,40,24,36])->latest()->get(['id','brandname','litpic','description','brandpay']);
        });
        $hotbrands=Cache::remember('mobile_index_hotbrands', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',2)->skip(10)->take(5)->orderBy('id','asc')->get(['id','brandname','litpic','description','brandpay']);
        });
        $muyingnavlists=Cache::remember('mobile_index_muyingnavlists', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',1)->orderBy('id','asc')->take(5)->get(['id','brandname','litpic','description','brandpay']);
        });
        //母婴生活馆
        $muyingshgnavlists=Cache::remember('mobile_index_muyingshgnavlists', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',2)->orderBy('id','asc')->take(5)->get(['id','brandname','litpic','description','brandpay']);
        });
        //品牌招商
        $muyingypnavlists=Cache::remember('mobile_index_muyingypnavlists', config('app.cachetime')+rand(60,60*24), function(){
            return Brandarticle::where('typeid',4)->orderBy('id','asc')->take(5)->get(['id','brandname','litpic','description','brandpay']);
        });
        $latestnews=Cache::remember('mobile_index_latestnews', config('app.cachetime')+rand(60,60*24), function(){
            return Archive::orderBy('id','desc')->take(7)->get(['id','title','litpic','created_at']);
        });
        return view('mobile.index',compact('cbrands','hotbrands','muyingnavlists','muyingshgnavlists','muyingypnavlists','latestnews'));
    }

}
