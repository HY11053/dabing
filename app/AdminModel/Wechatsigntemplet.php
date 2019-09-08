<?php

namespace App\AdminModel;

use Illuminate\Database\Eloquent\Model;

class Wechatsigntemplet extends Model
{
    protected $guarded=['_token','_method','image'];
}
