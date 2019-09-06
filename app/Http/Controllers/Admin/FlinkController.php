<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel\Flink;
use App\Http\Requests\FlinkValidationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlinkController extends Controller
{
    /**
     * 友情链接列表
     * @param
     *
     * @return
     */
    function Index()
    {
        $links=Flink::where('weburl','<>','')->paginate(30);
        return view('admin.flink',compact('links'));
    }

    /**
     * 创建友情链接
     * @param
     *
     * @return
     */

    function CreateFlink()
    {
        return view('admin.flink_create');
    }
    /**
     * 友情链接提交处理
     * @param
     *
     * @return
     */

    function PostCreateFlink(FlinkValidationRequest $request)
    {
        //dd($request->all());
        Flink::create($request->all());
        return redirect(action('Admin\FlinkController@Index'));

    }


    /**
     * 友情链接编辑
     * @param
     *
     * @return
     */

    function EditFlink($id)
    {
        $thislink=Flink::find($id);
        return view('admin.flink_edit',compact('thislink'));

    }
    /**
     * 友情链接编辑处理
     * @param
     *
     * @return
     */
    function PostEditFlink(FlinkValidationRequest $request,$id)
    {
        //dd($request->all());
        Flink::find($id)->update($request->all());
        return redirect(action('Admin\FlinkController@Index'));

    }
    /**
     * 友情链接删除
     * @param
     *
     * @return
     */
    function DeleteFlink($id)
    {
        Flink::find($id)->delete();
        return redirect(action('Admin\FlinkController@Index'));
    }
}
