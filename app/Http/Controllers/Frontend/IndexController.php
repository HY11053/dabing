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

        return view('frontend.index');
    }

}
