<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel\Loganysis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LogAccessInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function PcLogInfo(Request $request)
    {
        $arguments=$request->all();
        $loginfos=Loganysis::orderBy('id','desc')->where('infos','<>','')->when($request->mid, function ($query) use ($request) {
        return $query->where('mid',$request->mid-1);
    })->paginate(10000);
        return view('admin.logs',compact('loginfos','arguments'));
    }
}
