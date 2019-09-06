<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel\Wchatappletindex;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatIndexController extends Controller
{
    public function Indexlists()
    {
        $applists=Wchatappletindex::latest()->take(30)->orderBy('updated_at','desc')->paginate(30);
        return view('admin.wxappletlists',compact('applists'));
    }
    public function Create()
    {
        return view('admin.wxappletindex');
    }
}
