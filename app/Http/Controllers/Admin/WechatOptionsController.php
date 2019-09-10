<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel\Formid;
use App\AdminModel\Opendidstore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatOptionsController extends Controller
{
    public function FormidLists()
    {
        $idsources=Formid::latest()->paginate(30);
        return view('admin.wxappletformids',compact('idsources'));
    }

    public function OpenidLists()
    {
        $idsources=Opendidstore::latest()->paginate(30);
        return view('admin.wxappletopenids',compact('idsources'));
    }
}
