<?php

namespace App\Http\Controllers\Exem;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExemController extends Controller
{
    /**
     * 状态码构造方法
     * CartController constructor.
     */
    public function __construct()
    {
        $this->status = [
            "200" => "success",
            "40000" => "参数不能为空,注意字段",
            "40004" => "未知错误，加入购物车失败",
            "40010" => "找不到商品",
            "40011" => "找不到用户",
            "40012" => "购物车为空",
        ];
    }


    /**
     * fail返回值
     * @param null $code
     * @param null $msg
     * @param null $data
     * @return false|string
     */
    public function fail($code = null, $msg = null, $data = null)
    {
        $response = [
            "code" => $code,
            "msg" => $msg,
            "data" => $data
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);

    }

    public function list(Request $request)
    {
        $info=$request->input();
        $time=$info["time"];
        $sex=$info["sex"];
        if(empty($time) && empty($sex)){//查所有
            $data = UserModel::get()->toArray();//查询所有
        }else if(empty($sex) && $time==1){//查时间倒序
            $data=UserModel::orderBy("time","desc")->get()->toArray();
        }else if(empty($sex) && $time==2){//查时间正序
            $data=UserModel::orderBy("time","asc")->get()->toArray();
        }else if(empty($time) && $sex==1){//查男的
            $data=UserModel::where(["sex"=>1])->get()->toArray();
        }else if(empty($time) && $sex==2){//查女的
            $data=UserModel::where(["sex"=>2])->get()->toArray();
        }else if($time==1 && $sex==1){//时间倒序查男的
            $data=UserModel::where(["sex"=>1])->orderBy("time","desc")->get()->toArray();
        }else if($time==1 && $sex==2){//时间倒序查女的
            $data=UserModel::where(["sex"=>2])->orderBy("time","desc")->get()->toArray();
        }else if($time==2 && $sex==1){//时间正序查男的
            $data=UserModel::where(["sex"=>1])->orderBy("time","asc")->get()->toArray();
        }else if($time==2 && $sex==2){//时间正序查女的
            $data=UserModel::where(["sex"=>2])->orderBy("time","asc")->get()->toArray();
        }

        return $this->fail("200", $this->status['200'],$data);
    }
}
