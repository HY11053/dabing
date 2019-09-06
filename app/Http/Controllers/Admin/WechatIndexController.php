<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatIndexController extends Controller
{
    public function Create()
    {
        return view('admin.wxappletindex');
    }
}
