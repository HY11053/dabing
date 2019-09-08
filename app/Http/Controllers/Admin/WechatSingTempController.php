<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel\Wechatsigntemplet;
use App\Helpers\UploadImages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatSingTempController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    /**微信小程序单页模板列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Indexlists()
    {
        $applists=Wechatsigntemplet::latest()->take(30)->orderBy('updated_at','desc')->paginate(30);
        return view('admin.wxappletsignlists',compact('applists'));
    }

    /**微信小程序单页模板创建视图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Create()
    {
        return view('admin.wxappletsingcreate');
    }

    /**微信小程序单页模板添加处理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postCreate(Request $request)
    {
        if($request['image'])
        {
            $request['litpic']=UploadImages::UploadImage($request,'image');
        }
        if (Wechatsigntemplet::create($request->all())->wasRecentlyCreated)
        {
            return redirect(action('Admin\WechatSingTempController@Indexlists'));
        }
    }

    /**微信小程序单页模板编辑视图
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Editor($id)
    {
        $appletsign=Wechatsigntemplet::findOrFail($id);
        return view('admin.wxappletsign_edit',compact('appletsign'));
    }

    /**微信小程序单页模板编辑处理
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function PostEditor(Request $request,$id)
    {
        if($request['image'])
        {
            $request['litpic']=UploadImages::UploadImage($request,'image');
        }
        Wechatsigntemplet::findOrFail($id)->update($request->all());
        return redirect(action('Admin\WechatSingTempController@Indexlists'));
    }

    /**微信小程序单页模板删除
     * @param $id
     * @return string
     */
    public function Delete($id)
    {
        Wechatsigntemplet::findOrFail($id)->delete();
        return '删除成功';
    }
}
