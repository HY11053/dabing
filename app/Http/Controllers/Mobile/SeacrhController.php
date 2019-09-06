<?php

namespace App\Http\Controllers\Mobile;
use App\AdminModel\Archive;
use App\AdminModel\Brandarticle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeacrhController extends Controller
{
    public function SeacrhBrand(Request $request)
    {
        $pagelists=Brandarticle::orderBy('id','desc')->distinct()->when($request->typeid, function ($query) use ($request) {
            return $query->where('typeid',$request->typeid);
        })->when($request->tzid, function ($query) use ($request) {
            return $query->where('tzid',$request->tzid);
        })->when($request->acreage, function ($query) use ($request) {
            return $query->where('acreage',$request->acreage);
        })->when($request->title, function ($query) use ($request) {
            return $query->where('brandname','like','%'.$request->title.'%');
        })->paginate($perPage = 12);
        return view('mobile.search_brand',compact('pagelists'));
    }
}
