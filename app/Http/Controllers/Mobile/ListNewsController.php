<?php

namespace App\Http\Controllers\Mobile;

use App\AdminModel\Acreagement;
use App\AdminModel\Archive;
use App\AdminModel\Arctype;
use App\AdminModel\Area;
use App\AdminModel\Brandarticle;
use App\AdminModel\Production;
use Carbon\Carbon;
use App\Overwrite\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ListNewsController extends Controller
{
    /**文档列表 通配 包含普通文档 品牌文档及产品文档
     * @param $path
     * @param int $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listNews(Request $request,$path,$page=0)
    {
        $typeid=Arctype::where('real_path',preg_replace('/\/page\/[0-9]+/','',$request->path()))->value('id')?:abort(404);
        //当前栏目信息缓存
        $thistypeinfo=Cache::remember('thistypeinfos_'.$typeid,  config('app.cachetime')+rand(60,60*24), function() use($typeid){
            return  Arctype::where('id',$typeid)->first();
        });
        $cid=$path;
        //针对不同栏目类型返回不同类型页面
        //普通文档列表
        if($thistypeinfo->mid==0)
        {
            $pagelists=Archive::where('typeid',$typeid)->orderBy('id','desc')->paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page);
            $pagelists= Paginator::transfer(
                $cid,//传入分类id,
                $pagelists//传入原始分页器
            );
            $latestbrands=Cache::remember('mtype_latestbrands',   config('app.cachetime')+rand(60,60*24), function(){
                return Brandarticle::latest()->take(4)->orderBy('id','desc')->get(['id','brandname','brandpay','litpic','brandnum']);
            });
            $latestnews=Cache::remember('mtype_latestnews'.$thistypeinfo->id, config('app.cachetime')+rand(60,60*24), function() use($thistypeinfo){
                return Archive::where('typeid','<>',$thistypeinfo->id)->take(7)->latest()->get();
            });
            return view('mobile.list_article',compact('thistypeinfo','pagelists','latestbrands','latestnews'));
        }elseif ($thistypeinfo->mid==1)
        {
            $cid=preg_replace('/\/page\/[0-9]+/','',$path);

            $pagelists=Brandarticle::where('typeid',$thistypeinfo->id)->orderBy('id','desc')->distinct()->paginate($perPage = 12, $columns = ['*'], $pageName = 'page', $page);
            $pagelists= Paginator::transfer(
                $cid,//传入分类id,
                $pagelists//传入原始分页器
            );
            //店铺面积缓存
            $acreagements=Cache::remember('acreagements',  config('app.cachetime')+rand(60,60*24), function(){
                return Acreagement::pluck('type','id');
            });
            $cnewslists=Cache::remember('m_cnewslists'.$thistypeinfo->id,  rand(10,60), function() use($thistypeinfo){
                return Archive::whereIn('brandid',Brandarticle::where('typeid',$thistypeinfo->id)->latest()->pluck('id'))->take(7)->latest()->get(['id','title','litpic']);
            });
            return view('mobile.brands',compact('thistypeinfo','pagelists','cnewslists','acreagements'));
        }elseif($thistypeinfo->mid==2){
            $indexartopnavs=Arctype::where('mid',2)->take(10)->get(['typename','real_path']);
            return view('mobile.index_article',compact('thistypeinfo','indexartopnavs'));
        }

    }

    /**品牌分类筛选
     * @param $path
     * @param $tid
     * @param $cid
     * @param $zid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function projectBrandLists(Request $request,$path,$id)
    {
        //栏目查找
        $thistypeinfo=Arctype::where('real_path',$path)->first();
        if (empty($thistypeinfo))
        {
            abort(404);
        }
        $cnewslists=Cache::remember('m_cnewslists'.$thistypeinfo->id,  rand(10,60), function() use($thistypeinfo){
            return Archive::whereIn('brandid',Brandarticle::where('typeid',$thistypeinfo->id)->latest()->pluck('id'))->take(7)->latest()->get(['id','title','litpic']);
        });
        //店铺面积缓存
        $acreagements=Cache::remember('acreagements',  config('app.cachetime')+rand(60,60*24), function(){
            return Acreagement::pluck('type','id');
        });
        $investments=Cache::remember('investments',  config('app.cachetime')+rand(60,60*24), function(){
            return InvestmentType::orderBy('id','asc')->pluck('type','id');
        });
        if (isset(explode('/',$request->path())[2]))
        {
            preg_match('#[a-z]#',explode('/',$request->path())[2],$match);
            switch ($match[0])
            {
                case "p":
                    $pagelists=Brandarticle::where('typeid',$thistypeinfo->id)->where('tzid',$id)->orderBy('id','desc')->distinct()->paginate(15);
                    $title=$investments[$id];
                    break;
                case "m":
                    $pagelists=Brandarticle::where('typeid',$thistypeinfo->id)->where('acreage',$id)->orderBy('id','desc')->distinct()->paginate(15);
                    $title=$acreagements[$id].'㎡';
                    break;
                case "a":
                    $pagelists=Brandarticle::where('typeid',$thistypeinfo->id)->where('brandorigin',Area::where('id',$id)->value('regionname'))->orderBy('id','desc')->distinct()->paginate(15);
                    $title=Area::where('id',$id)->value('regionname');
                    break;
                default:
                    dd(11);
                    $pagelists=Brandarticle::orderBy('id','desc')->distinct()->paginate(15);
                    $title='';
            }
        }
        return view('mobile.project_brands',compact('thistypeinfo','pagelists','cnewslists','acreagements','investments','title'));
    }


}
