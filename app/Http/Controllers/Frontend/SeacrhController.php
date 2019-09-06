<?php

namespace App\Http\Controllers\Frontend;
use App\AdminModel\Archive;
use App\AdminModel\Arctype;
use App\AdminModel\Area;
use App\AdminModel\Brandarticle;
use App\Overwrite\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SeacrhController extends Controller
{
    public function SeacrhBrand(Request $request,$page=0)
    {
        $topbrandnavs=Cache::remember('topbrandnavs', config('app.cachetime')+60*24*365, function() {
            return Arctype::where('mid',1)->orderBy('id','asc')->get(['real_path','typename']);
        });
        $paihangbangs= Cache::remember('phb', config('app.cachetime')+rand(60,60*24), function() {
            return  Brandarticle::take('10')->orderBy('click','desc')->get(['id','brandname','litpic','brandnum','brandpay','description']);
        });
        $flashlingshibrands=Cache::remember('flashlingshibrands', config('app.cachetime')+rand(60,60*24), function() {
            return Brandarticle::skip(10)->take(9)->orderBy('click','desc')->get(['id','brandname','litpic']);
        });
        $cnewslists=Cache::remember('cnewslists',  rand(10,60), function(){
            return Archive::take(13)->latest()->get(['id','title']);
        });
        $hotbrands=Cache::remember('hotbrands', config('app.cachetime')+rand(60,60*24), function() {
            return Brandarticle::skip(19)->take(6)->orderBy('click','desc')->get(['id','brandname','brandpay','brandnum','brandnum','litpic']);
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

        $arealists=Cache::remember('arealists',  config('app.cachetime')+rand(60,60*24), function(){
            $brandorigins=Brandarticle::select('brandorigin')->distinct()->take(20)->pluck('brandorigin');
            foreach ($brandorigins as $brandorigin)
            {
                $area=Area::where('regionname',$brandorigin)->first(['id','regionname']);
                if (!empty($area))
                {
                    $areas[]=$area;
                }
            }
            return $areas;
        });
        $cid='search';
        $pagelists=Brandarticle::orderBy('id','desc')->distinct()->when($request->typeid, function ($query) use ($request) {
            return $query->where('typeid',$request->typeid);
        })->when($request->tzid, function ($query) use ($request) {
            return $query->where('tzid',$request->tzid);
        })->when($request->acreage, function ($query) use ($request) {
            return $query->where('acreage',$request->acreage);
        })->when($request->title, function ($query) use ($request) {
            return $query->where('brandname','like','%'.$request->title.'%');
        })->paginate($perPage = 12, $columns = ['*'], $pageName = 'page', $page);
        $pagelists= Paginator::transfer(
            $cid,//传入分类id,
            $pagelists//传入原始分页器
        );
        return view('frontend.search_brand',compact('topbrandnavs','paihangbangs','flashlingshibrands','cnewslists','hotbrands','acreagements','investments','touziids','arealists','pagelists'));
    }
}
