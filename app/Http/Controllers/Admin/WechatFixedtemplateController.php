<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel\Wchatappletindex;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatFixedtemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    /**小程序固定模板列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Fixedtemplatelists()
    {
        $fixedtemplatelists=Wchatappletindex::latest()->take(30)->orderBy('updated_at','desc')->paginate(30);
        return view('admin.wxappfixedtemplatelist',compact('fixedtemplatelists'));
    }

    /**小程序固定模板视图创建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function FixedtemplateCreate()
    {
        return view('admin.wxappfixedtemplatecreate');
    }

    /**小程序固定模板创建处理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function FixedtemplatePostCreate(Request $request)
    {
        $request['editor']=auth('admin')->user()->name;
        $request['editor_id']=auth('admin')->id();
        if (Wchatappletindex::create($request->all())->wasRecentlyCreated)
        {
            return redirect(action('Admin\WechatFixedtemplateController@Fixedtemplatelists'));
        }
    }

    /**小程序固定模板更新视图
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function FixedtemplateEditor($id)
    {
        $thisfixedtemplate=Wchatappletindex::findOrFail($id);
        return view('admin.wxappfixedtemplate_edit',compact('thisfixedtemplate'));
    }

    /**小程序固定模板更新处理
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function FixedtemplatePostEditor(Request $request,$id)
    {
        Wchatappletindex::findOrFail($id)->update($request->all());
        return redirect(action('Admin\WechatFixedtemplateController@Fixedtemplatelists'));
    }

    public function FixedtemplateDelete($id)
    {
        Wchatappletindex::where('id',$id)->delete();
        return '删除成功';
    }
}
